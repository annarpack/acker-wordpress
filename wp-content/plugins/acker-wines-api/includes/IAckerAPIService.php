<?php


namespace AckerWines\Api;


interface IAckerAPIService
{
    function globalSearch(string $search_term, string $search_category, int $post_count);

    function getPurchaseHistory();

    function getPortfolioWines();

    function getFavorites();

    function getWishlist();

    function getDashboard();

    function removeFavorite(string $product_id, string $wishlist_id);

    function saveAppraisalTable(string $page, string $data);
}
