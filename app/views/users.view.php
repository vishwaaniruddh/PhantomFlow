<?php  require 'partials/head.php'; ?>


<div class="pcoded-content">
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="page-body">
                    <div class="card">
                        <div class="card-block">

<?php 

foreach($result as $rekey=>$revalue) :?>

    <li><?= $revalue->name; ?></li>
<? endforeach; 

?>
<style>
    .pagination {
    display: flex;
    justify-content: center;
    margin-top: 20px;
}

/* Pagination links */
.pagination a {
    padding: 6px 12px;
    border: 1px solid #ccc;
    margin: 0 4px;
    text-decoration: none;
    color: #333;
}

/* Active page link */
.pagination a.active {
    background-color: #007bff;
    color: white;
    border-color: #007bff;
}
</style>
<div class="pagination">
    <?php for ($page = 1; $page <= ceil($totalUsers / $usersPerPage); $page++): ?>
        <a href="?page=<?= $page ?>" <?= ($page == $currentPage) ? 'class="active"' : '' ?>><?= $page ?></a>
    <?php endfor; ?>
</div>


<form action="users" method="POST">
    <input type="text" name="name">
    <input type="submit">
</form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php  require 'partials/footer.php'; ?>
