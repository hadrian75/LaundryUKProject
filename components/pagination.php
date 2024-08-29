<?php
$nowPage = strtok($_SERVER['REQUEST_URI'], '?'); // Get the current page URL without query parameters
$currentQuery = $_SERVER['QUERY_STRING']; // Get the current query parameters

// Construct the base URL with existing query parameters
$baseUrl = $nowPage;
if ($currentQuery !== '') {
    $baseUrl .= '?' . $currentQuery;
}

// Remove the 'viewPage' parameter from the query string
$baseUrl = preg_replace('/[&?]viewPage=\d+/', '', $baseUrl);
$baseUrl = rtrim($baseUrl, '&?'); // Remove trailing '&' or '?'
?>
<div class="flex justify-end p-2 <?php if ($total_records_query <= 10) {
    echo "hidden";
} ?>">
    <nav aria-label="Page navigation example">
        <ul class="inline-flex -space-x-px text-sm">
            <li class="<?php if ($current_page == 1) {
                echo "hidden";
            } ?>">
                <a href="<?= $baseUrl . '&viewPage=' . ($current_page - 1) ?>"
                    class="flex items-center justify-center px-3 h-8 ms-0 leading-tight text-gray-500 bg-white border border-1 border-gray-300 rounded-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                    << Before </a>
            </li>
            <?php
            $min_page = max(1, $current_page - 3);
            $max_page = min($total_pages, $current_page + 3);

            for ($i = $min_page; $i <= $max_page; $i++) {
                ?>
                <li>
                    <a href="<?= $baseUrl . '&viewPage=' . $i ?>"
                        class="flex items-center justify-center px-3 h-8 ms-0 leading-tight text-gray-500 bg-white border border-1 border-gray-300 rounded-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                        <?= $i ?>
                    </a>
                </li>
            <?php } ?>
            <li class="<?php if ($current_page == $total_pages) {
                echo "hidden";
            } ?>">
                <a href="<?= $baseUrl . '&viewPage=' . ($current_page + 1) ?>"
                    class="flex items-center justify-center px-3 h-8 ms-0 leading-tight text-gray-500 bg-white border border-1 border-gray-300 rounded-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                    Next >>
                </a>
            </li>
        </ul>
    </nav>
</div>