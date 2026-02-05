<?php


function getPaginationInfo($items, $limit)
{
    return [
        'limit' => $limit,
        'currentPage' => $items->currentPage(),
        'previousPageUrl' => $items->previousPageUrl() ? $items->previousPageUrl() . '&limit=' . $limit : null,
        'nextPageUrl' => $items->nextPageUrl() ? $items->nextPageUrl() . '&limit=' . $limit : null,
        'totalPage' => $items->lastPage(),
        'total' => $items->total(),
    ];
}
