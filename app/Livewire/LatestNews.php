<?php

namespace App\Livewire;

use GuzzleHttp\Client;
use Livewire\Component;
use App\Services\HttpService;

class LatestNews extends Component
{
    public $selectedApi;
    public $news;
    protected $httpService;

    protected $allowedApis = [
        'https://newsapi.org/v2/top-headlines?country=it&apiKey=5fbe92849d5648eabcbe072a1cf91473',
        'https://newsapi.org/v2/top-headlines?country=gb&apiKey=5fbe92849d5648eabcbe072a1cf91473',
        'https://newsapi.org/v2/top-headlines?country=us&apiKey=5fbe92849d5648eabcbe072a1cf91473',
    ];

    public function __construct()
    {
        $this->httpService = app(HttpService::class);
    }

    public function fetchNews()
    {
        if (filter_var($this->selectedApi, FILTER_VALIDATE_URL) === FALSE) {
            $this->news = 'Invalid URL';
            return;
        }

        // Validazione: l'URL deve essere esattamente uno di quelli previsti
        if (!in_array($this->selectedApi, $this->allowedApis)) {
            $this->news = 'Selected source not allowed';
            return;
        }

        $this->news = json_decode($this->httpService->getRequest($this->selectedApi), true);
    }

    public function render()
    {
        return view('livewire.latest-news');
    }
}