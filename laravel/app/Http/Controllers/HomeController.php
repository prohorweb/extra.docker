<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $host = $request->getHost();

        $itemClubs = [
            [
                'name' => 'ТРЦ «Питер»',
                'address' => 'Санкт-Петербург, ул. Типанова, 21',
                'image' => '/img/clubs/welcom-block-img-2.jpg',
                'url' => 'http://piter.' . $host,
            ],
            [
                'name' => '«Матроса железняка»',
                'address' => 'Санкт-Петербург, ул. Матроса Железняка, 57А',
                'image' => '/img/clubs/welcom-block-img-4.jpg',
                'url' => 'http://matros.' . $host,
            ],
            [
                'name' => 'De-Vision',
                'address' => 'Санкт-Петербург, пр. Культуры, 1',
                'image' => '/img/clubs/welcom-block-img-5.jpg',
                'url' => 'http://de-vision.new',
            ],
        ];

       $placemarks = [
            [
                'coordinates' => '59.8499, 30.2953',
                'hint'        => 'EXTRASPORT Питер',
                'icon'        => 'uploads/layout/icones/marker.png',
                'url'         => 'http://piter.' . $host,
            ],
            [
                'coordinates' => '59.9880, 30.3605',
                'hint'        => 'EXTRASPORT Матроса Железняка',
                'icon'        => 'uploads/layout/icones/marker.png',
                'url'         => 'http://matros.' . $host,
            ],
            [
                'coordinates' => '60.0335, 30.3683',
                'hint'        => 'De-vision',
                'icon'        => 'uploads/layout/icones/marker2.png',
                'url'         => 'http://de-vision.new',
            ],
        ];


        if (in_array($host, ['extra.new', 'www.extra.new'])) {
            return view('pages.welcome', [
                'hero' => [
                    'video' => '/video/bg_moution.mp4',
                    'logo' => asset('img/logo.svg'),
                    'heading' => 'Сеть фитнес клубов на результат!',
                    'cta' => [
                        'text' => 'Выберите клуб',
                        'url' => '#clubs',
                        'url-mobile' => '#clubs-mobile',
                    ],
                ],
                'clubs' => $itemClubs,
                'placemarks' => $placemarks,
                'seo' => null,
            ]);
        }

        if (in_array($host, ['de-vision.new', 'www.de-vision.new']) || str_contains($host, '.extra.new')) {
            $subdomain = explode('.', $host)[0] ?? 'club';
            $clubTitles = [
                'piter' => 'EXTRASPORT ТК «ПИТЕР»',
                'matros' => 'EXTRASPORT ул. Матроса Железняка',
            ];
            $clubTitle = $clubTitles[$subdomain] ?? 'EXTRASPORT ' . ucfirst($subdomain);

            return view('pages.home', [
                'club' => ['name' => $clubTitle],
                'hero' => [
                    'heading' => 'Сеть фитнес клубов на результат!',
                    'subheading' => 'Ваш клуб - ' . $clubTitle,
                    'showLogo' => false,
                ],
                'services' => [
                    ['title' => 'Тренажерный зал', 'description' => 'Современное оборудование для любых целей'],
                    ['title' => 'Групповые программы', 'description' => 'Йога, пилатес, зумба и более 30 направлений'],
                    ['title' => 'Персональные тренировки', 'description' => 'Индивидуальные программы с тренером'],
                ],
                'theme' => [],
                'seo' => null,
            ]);
        }

        return view('pages.welcome', [
            'clubs' => $itemClubs,
            'placemarks' => $placemarks,
            'seo' => null,
        ]);
    }
}

