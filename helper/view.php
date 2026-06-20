<?php

/* ==================================================
 * VIEW
 * ================================================== */

/**
 * Render phân trang Bootstrap.
 *
 * Ví dụ:
 *
 * echo pager([
 *     'page'  => 2,
 *     'total' => 10,
 *     'query' => $_GET
 * ]);
 */
function pager(array $params = []): string
{
    static $config = null;

    if ($config === null) {
        $config = [
            'window' => 2
        ];
    }

    if (empty($params)) {
        return $config;
    }

    $page  = (int) ($params['page'] ?? 1);
    $total = (int) ($params['total'] ?? 1);
    $query = (array) ($params['query'] ?? []);

    $color = ($params['color'] ?? false)
        ? 'success'
        : 'secondary';

    $build = function (int $p) use ($query): string {

        $query['page'] = $p;

        return '?'
            . http_build_query($query);
    };

    ob_start();
    ?>

    <?php if ($total > 1): ?>

        <nav class="mt-3 d-flex">
            <ul class="pagination pagination-sm shadow-sm mb-0">

                <?php if ($page > 1): ?>

                    <li class="page-item">
                        <a
                            class="page-link text-<?= $color ?>"
                            href="<?= $build(1) ?>"
                        >
                            « Đầu
                        </a>
                    </li>

                    <li class="page-item">
                        <a
                            class="page-link text-<?= $color ?>"
                            href="<?= $build($page - 1) ?>"
                        >
                            ‹
                        </a>
                    </li>

                <?php endif; ?>

                <?php
                    $start = max(
                        1,
                        $page - $config['window']
                    );

                    $end = min(
                        $total,
                        $page + $config['window']
                    );
                ?>

                <?php if ($start > 1): ?>

                    <li class="page-item disabled">
                        <span class="page-link">
                            ...
                        </span>
                    </li>

                <?php endif; ?>

                <?php for ($i = $start; $i <= $end; $i++): ?>

                    <li class="page-item <?= $i === $page ? 'active' : '' ?>">

                        <?php if ($i === $page): ?>

                            <span
                                class="page-link border-<?= $color ?> text-<?= $color ?> bg-light"
                            >
                                <?= $i ?>
                            </span>

                        <?php else: ?>

                            <a
                                class="page-link text-<?= $color ?>"
                                href="<?= $build($i) ?>"
                            >
                                <?= $i ?>
                            </a>

                        <?php endif; ?>

                    </li>

                <?php endfor; ?>

                <?php if ($end < $total): ?>

                    <li class="page-item disabled">
                        <span class="page-link">
                            ...
                        </span>
                    </li>

                <?php endif; ?>

                <?php if ($page < $total): ?>

                    <li class="page-item">
                        <a
                            class="page-link text-<?= $color ?>"
                            href="<?= $build($page + 1) ?>"
                        >
                            ›
                        </a>
                    </li>

                    <li class="page-item">
                        <a
                            class="page-link text-<?= $color ?>"
                            href="<?= $build($total) ?>"
                        >
                            Cuối »
                        </a>
                    </li>

                <?php endif; ?>

            </ul>
        </nav>

    <?php endif; ?>

    <?php

    return ob_get_clean();
}