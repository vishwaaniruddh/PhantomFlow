<?php  require 'partials/head.php'; ?>


<?php 

foreach($result as $rekey=>$revalue) :?>

    <li><?= $revalue->name; ?></li>
<? endforeach; ?>


<form action="users" method="POST">
    <input type="text" name="name">
    <input type="submit">
</form>

<?php  require 'partials/footer.php'; ?>
