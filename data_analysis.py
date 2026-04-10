import sys
import os
import json

try:
    import pandas as pd
    import numpy as np
    import plotly.express as px
    from sklearn.linear_model import LinearRegression
    from datetime import datetime, timedelta
    from sqlalchemy import create_engine, text
except ImportError as e:
    print(f"Error: Missing dependency. {str(e)}")
    sys.exit(1)

def get_database_connection():
    return create_engine('mysql+mysqlconnector://root:@localhost/smart_inventory_db')

def fetch_sales_data():
    try:
        engine = get_database_connection()
        query = """
            SELECT s.TransactionID, s.ItemID, i.ItemName, s.SaleDate, s.QuantitySold, s.TotalPrice 
            FROM SalesTransaction s
            JOIN InventoryItem i ON s.ItemID = i.ItemID;
        """
        df = pd.read_sql(query, con=engine)
        return df
    except Exception as e:
        print(f"Database Error: {str(e)}")
        sys.exit(1)

def fetch_inventory_data():
    try:
        engine = get_database_connection()
        query = "SELECT ItemID, ItemName, Quantity FROM InventoryItem;"
        df = pd.read_sql(query, con=engine)
        return df
    except Exception as e:
        print(f"Inventory Fetch Error: {str(e)}")
        return pd.DataFrame()

def process_data(df):
    df['SaleDate'] = pd.to_datetime(df['SaleDate'])
    df['MonthYear'] = df['SaleDate'].dt.to_period('M')
    df.fillna(0, inplace=True)
    return df

def predict_sales(df):
    model = LinearRegression()
    predictions = []

    df_monthly = df.groupby(['ItemID', 'MonthYear', 'ItemName']).agg({
        'QuantitySold': 'sum'
    }).reset_index()

    future_months = [
        pd.Period((datetime.now() + timedelta(days=i * 30)).strftime('%Y-%m'), freq='M')
        for i in range(1, 7)
    ]

    for item_id in df['ItemID'].unique():
        item_df = df_monthly[df_monthly['ItemID'] == item_id]
        item_name = df[df['ItemID'] == item_id]['ItemName'].iloc[0]

        if item_df.empty:
            for month in future_months:
                predictions.append({
                    'ItemID': int(item_id),
                    'ItemName': item_name,
                    'PredictedRestockDate': month.start_time.strftime('%Y-%m-%d'),
                    'PredictedQuantity': 0
                })
            continue

        if len(item_df) == 1:
            last_qty = int(item_df['QuantitySold'].iloc[0])
            for month in future_months:
                predictions.append({
                    'ItemID': int(item_id),
                    'ItemName': item_name,
                    'PredictedRestockDate': month.start_time.strftime('%Y-%m-%d'),
                    'PredictedQuantity': last_qty
                })
            continue

        X = item_df['MonthYear'].apply(lambda x: x.ordinal).values.reshape(-1, 1)
        y = item_df['QuantitySold'].values
        model.fit(X, y)

        future_X = np.array([m.ordinal for m in future_months]).reshape(-1, 1)
        predicted_sales = model.predict(future_X)

        for i, month in enumerate(future_months):
            qty = max(0, int(predicted_sales[i]))
            qty = min(qty, 1000)
            predictions.append({
                'ItemID': int(item_id),
                'ItemName': item_name,
                'PredictedRestockDate': month.start_time.strftime('%Y-%m-%d'),
                'PredictedQuantity': qty
            })

    return predictions

def save_predictions_analysis(df, inventory_df):
    try:
        engine = get_database_connection()

        df['SaleDate'] = pd.to_datetime(df['SaleDate'])
        daily_sales = df.groupby(['ItemID', 'SaleDate'])['QuantitySold'].sum().reset_index()

        results = []
        for item_id in df['ItemID'].unique():
            item_daily = daily_sales[daily_sales['ItemID'] == item_id]['QuantitySold']

            avg_daily = round(float(item_daily.mean()), 2)
            max_daily = round(float(item_daily.max()), 2)
            safety_stock = round(max_daily * 7, 2)
            reorder_point = round((avg_daily * 14) + safety_stock, 2)

            current_stock = 0
            if not inventory_df.empty:
                inv_row = inventory_df[inventory_df['ItemID'] == item_id]
                if not inv_row.empty:
                    current_stock = int(inv_row['Quantity'].iloc[0])

            if current_stock <= reorder_point:
                decision = 'Reorder Now'
            elif current_stock <= reorder_point * 1.5:
                decision = 'Reorder Soon'
            else:
                decision = 'Stock OK'

            results.append({
                'product_id': int(item_id),
                'avg_daily_sales': avg_daily,
                'max_daily_sales': max_daily,
                'safety_stock': safety_stock,
                'reorder_point': reorder_point,
                'current_stock': current_stock,
                'decision': decision
            })

        with engine.connect() as conn:
            conn.execute(text("DELETE FROM predictions"))
            conn.commit()

            for r in results:
                conn.execute(text("""
                    INSERT INTO predictions 
                    (product_id, avg_daily_sales, max_daily_sales, safety_stock, reorder_point, current_stock, decision)
                    VALUES (:product_id, :avg_daily_sales, :max_daily_sales, :safety_stock, :reorder_point, :current_stock, :decision)
                """), r)
            conn.commit()

        print(f"Predictions analysis saved: {len(results)} records.")

    except Exception as e:
        print(f"Error saving predictions analysis: {str(e)}")

def main():
    os.makedirs('predictions', exist_ok=True)
    os.makedirs('plots', exist_ok=True)

    print("Fetching data...")
    df = fetch_sales_data()

    if df.empty:
        print("No sales data found in database.")
        return

    print("Fetching inventory...")
    inventory_df = fetch_inventory_data()

    print("Processing data...")
    df = process_data(df)

    print("Generating predictions...")
    predictions = predict_sales(df)

    output_path = 'predictions/predicted_restock.json'
    with open(output_path, 'w') as f:
        json.dump(predictions, f, indent=4)

    print("Saving predictions analysis to database...")
    save_predictions_analysis(df, inventory_df)

    try:
        fig = px.bar(df.groupby('ItemName')['QuantitySold'].sum().reset_index(),
                     x='ItemName', y='QuantitySold', title='Total Sales by Item')
        fig.write_html('plots/sales_distribution.html')
        print("Plots generated.")
    except Exception as e:
        print(f"Plotting Error: {str(e)}")

    print(f"Success: Predictions saved to {output_path}")

if __name__ == "__main__":
    main()