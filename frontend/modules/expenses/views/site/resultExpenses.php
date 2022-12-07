<b>Общие расходы:</b> <?= $commonExpenses[0]['commonEx']; ?>

<?php foreach ($usersExpenses as $user): ?>
<?= $user['fullName']; ?> : <?= ($user['commonEx']>0)?'В плюсе': 'В минусе'; ?> <?= $user['commonEx']; ?>

<?php endforeach; ?>