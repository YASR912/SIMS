<?php
include 'head.php';
require 'send_otp.php';

$query = "
    SELECT i.ItemID, i.ItemName, pr.reorder_point, pr.current_stock, pr.decision, pr.last_state
    FROM predictions pr
    JOIN inventoryitem i ON pr.product_id = i.ItemID
";

$result = $con->query($query);

while ($row = $result->fetch_assoc()) {

    $product_id = $row['ItemID'];

    // 🔥 نعمل بصمة للحالة الحالية
    $current_state = md5(
        $row['current_stock'] . '-' .
        $row['reorder_point'] . '-' .
        $row['decision']
    );

    // ✅ لو الحالة اتغيرت
    if ($row['last_state'] !== $current_state) {

        // 📧 فقط لو محتاج reorder
        if (in_array($row['decision'], ['Reorder Now', 'Reorder Soon'])) {

            $users = $con->query("
                SELECT FirstName, Email 
                FROM user
                WHERE role IN ('Admin','Manager','Employee')
            ");

            while ($user = $users->fetch_assoc()) {

                $message = "
                <h2>Hello {$user['FirstName']} 👋</h2>

                <p><b>Product:</b> {$row['ItemName']}</p>
                <p><b>Stock:</b> {$row['current_stock']}</p>
                <p><b>Reorder Point:</b> {$row['reorder_point']}</p>
                <p><b>Status:</b> {$row['decision']}</p>

                <p style='color:red;'>
                ⚠️ New change detected in inventory!
                </p>
                ";

                sendMail(
                    $user['Email'],
                    "⚠️ Smart Inventory Update",
                    $message
                );
            }
        }

        // ✅ تحديث الحالة (حتى لو ما أرسل)
        $con->query("
            UPDATE predictions 
            SET last_state = '$current_state'
            WHERE product_id = $product_id
        ");
    }
}

echo "✅ Smart Alerts Done";
?>