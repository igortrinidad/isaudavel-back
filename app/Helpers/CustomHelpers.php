<?php


use Illuminate\Pagination\LengthAwarePaginator;

if (! function_exists('custom_paginator')) {
    function custom_paginator(LengthAwarePaginator $paginator, $key_name = 'data')
    {
       $pagination = [
           'current_page' => $paginator->currentPage(),
           'from' => $paginator->firstItem(),
           'last_page' => $paginator->lastPage(),
           'next_page_url' => $paginator->nextPageUrl(),
           'per_page' => $paginator->perPage(),
           'prev_page_url' => $paginator->previousPageUrl(),
           'to' => $paginator->lastItem(),
           'total' => $paginator->total(),
       ];

       return [ $key_name => $paginator->items(), 'pagination' => $pagination ];

    }
}