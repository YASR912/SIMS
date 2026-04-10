<?php
include 'auth.php';
include 'head.php';

$predictionFile = 'predictions/predicted_restock.json';

if (file_exists($predictionFile)) {
    $predictionData = json_decode(file_get_contents($predictionFile), true);
    $insertedCount = 0;

    foreach ($predictionData as $prediction) {
        if (empty($prediction['PredictedRestockDate']) || $prediction['PredictedQuantity'] <= 0) {
            continue;
        }

        $itemID = $prediction['ItemID'];
        $predictedRestockDate = $prediction['PredictedRestockDate'];
        $predictedQuantity = $prediction['PredictedQuantity'];

        $checkQuery = "SELECT * FROM prediction WHERE ItemID = ? AND PredictedRestockDate = ?";
        if ($checkStmt = $con->prepare($checkQuery)) {
            $checkStmt->bind_param("is", $itemID, $predictedRestockDate);
            $checkStmt->execute();
            $checkStmt->store_result();

            if ($checkStmt->num_rows == 0) {
                $insertQuery = "INSERT INTO prediction (ItemID, PredictedRestockDate, PredictedQuantity) VALUES (?, ?, ?)";
                if ($stmt = $con->prepare($insertQuery)) {
                    $stmt->bind_param("isi", $itemID, $predictedRestockDate, $predictedQuantity);
                    if ($stmt->execute()) {
                        $insertedCount++;
                    }
                    $stmt->close();
                }
            }
            $checkStmt->close();
        }
    }

    echo "<script>
        alert('Predictions processed successfully. $insertedCount new records inserted.');
        window.location.href = 'predictive_analysis.php';
    </script>";
} else {
    echo "<script>
        alert('No prediction data found.');
        window.location.href = 'predictive_analysis.php';
    </script>";
}
?>