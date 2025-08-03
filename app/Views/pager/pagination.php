<?php if ($pager->getPageCount() > 1) : ?>
<nav>
    <ul class="pagination justify-content-center">
        <!-- Tombol Previous -->
        <?php if ($pager->hasPrevious()) : ?>
            <li class="page-item">
                <a href="<?= $pager->getPreviousPageURI() ?>" class="page-link" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span> Previous
                </a>
            </li>
        <?php else : ?>
            <li class="page-item disabled">
                <span class="page-link"><span aria-hidden="true">&laquo;</span> Previous</span>
            </li>
        <?php endif ?>

        <!-- Nomor Halaman -->
        <?php foreach ($pager->links() as $link) : ?>
            <li class="page-item <?= $link['active'] ? 'active' : '' ?>">
                <a href="<?= $link['uri'] ?>" class="page-link"><?= $link['title'] ?></a>
            </li>
        <?php endforeach ?>

        <!-- Tombol Next -->
        <?php if ($pager->hasNext()) : ?>
            <li class="page-item">
                <a href="<?= $pager->getNextPageURI() ?>" class="page-link" aria-label="Next">
                    Next <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        <?php else : ?>
            <li class="page-item disabled">
                <span class="page-link">Next <span aria-hidden="true">&raquo;</span></span>
            </li>
        <?php endif ?>
    </ul>
</nav>
<?php endif ?>