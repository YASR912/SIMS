import mysql.connector

# DB connection
conn = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="smart_inventory_db"
)

cursor = conn.cursor(dictionary=True)

# Fetch products with sales history
cursor.execute("""
SELECT p.id AS product_id, p.stock_quantity,
       s.quantity
FROM products p
JOIN sales s ON p.id = s.product_id
""")

rows = cursor.fetchall()

# Group sales by product
products = {}
for row in rows:
    pid = row["product_id"]
    if pid not in products:
        products[pid] = {
            "sales": [],
            "stock": row["stock_quantity"]
        }
    products[pid]["sales"].append(row["quantity"])

lead_time = 5

for pid, data in products.items():
    daily_sales = data["sales"]
    current_stock = data["stock"]

    avg_sales = sum(daily_sales) / len(daily_sales)
    max_sales = max(daily_sales)

    safety_stock = int((max_sales - avg_sales) * lead_time)
    rop = int((avg_sales * lead_time) + safety_stock)

    decision = "REORDER" if current_stock <= rop else "OK"

    # Save prediction
    cursor.execute("""
        INSERT INTO predictions
        (product_id, avg_daily_sales, max_daily_sales,
         safety_stock, reorder_point, current_stock, decision)
        VALUES (%s,%s,%s,%s,%s,%s,%s)
    """, (
        pid, avg_sales, max_sales,
        safety_stock, rop, current_stock, decision
    ))

conn.commit()
cursor.close()
conn.close()

print("Predictions calculated and saved successfully.")