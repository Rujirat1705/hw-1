<?php
/* ---------- ส่วนประมวลผล (ถูกเรียกเมื่อฟอร์มถูกส่ง) ---------- */
$coins = [10, 5, 1]; // เหรียญที่ใช้ทอน มากไปน้อย

$price = isset($_POST['price']) ? (int)$_POST['price'] : 0;
$money = isset($_POST['money']) ? (int)$_POST['money'] : 0;

$change      = 0;
$changeCoins = []; // เก็บจำนวนเหรียญแต่ละชนิด

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $price > 0 && $money > 0) {
    $change = $money - $price;
    if ($change >= 0) {
        $remaining = $change;
        foreach ($coins as $c) {
            $changeCoins[$c] = intdiv($remaining, $c);
            $remaining       = $remaining % $c;
        }
    } else {
        $change = -1; // ส่งสัญญาณว่าเงินไม่พอ
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>โปรแกรมคำนวณเงินทอน</title>
    <style>
        body { font-family: "Prompt", sans-serif; background:#f5f7fa; margin:0; padding:40px; }
        .box { max-width:500px; margin:auto; background:#fff; padding:30px; border-radius:8px; box-shadow:0 2px 8px rgba(0,0,0,.1); }
        h1 { text-align:center; color:#333; margin-top:0; }
        label { display:block; margin-top:15px; font-weight:600; }
        input[type=number] { width:100%; padding:10px; font-size:1.1em; border:1px solid #ccc; border-radius:4px; box-sizing:border-box; }
        button { width:100%; margin-top:25px; padding:12px; font-size:1.1em; background:#007bff; color:#fff; border:none; border-radius:4px; cursor:pointer; }
        button:hover { background:#0069d9; }
        .result { margin-top:30px; line-height:1.6; }
        .result ul { list-style:none; padding:0; }
        .result li { padding:4px 0; }
        .error { color:#e63946; font-weight:600; }
    </style>
</head>
<body>
<div class="box">
    <h1>โปรแกรมคำนวณเงินทอน</h1>

    <form method="post" autocomplete="off">
        <label for="price">ราคาสินค้า (บาท)</label>
        <input type="number" min="1" name="price" id="price" value="<?=htmlspecialchars($price)?>" required>

        <label for="money">เงินที่ลูกค้าจ่าย (บาท)</label>
        <input type="number" min="1" name="money" id="money" value="<?=htmlspecialchars($money)?>" required>

        <button type="submit">คำนวณเงินทอน</button>
    </form>

    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        <div class="result">
            <?php if ($change === -1): ?>
                <p class="error">เงินที่ลูกค้าจ่ายไม่พอค่ะ! กรุณาตรวจสอบใหม่</p>
            <?php else: ?>
                <p><strong>ต้องทอนเงิน <?=number_format($change)?> บาท</strong></p>
                <p>ประกอบด้วย</p>
                <ul>
                <?php foreach ($changeCoins as $value => $qty): ?>
                    <li>เหรียญ <?=number_format($value)?> บาท = <?=number_format($qty)?> เหรียญ</li>
                <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
</body>
</html>