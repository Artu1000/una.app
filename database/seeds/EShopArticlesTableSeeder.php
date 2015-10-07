<?php

use Illuminate\Database\Seeder;

class EShopArticlesTableSeeder extends Seeder
{
    public function run()
    {
        $e_shop_article_repo = App::make(App\Repositories\EShop\EShopArticleRepositoryInterface::class);

        $e_shop_article_repo->createMultiple([
            [
                'category_id' => config('e-shop.article_category_key.textile'),
                'order_type_id' => config('e-shop.order_type_key.in-stock'),
                'title' => 'Combinaison règlementaire',
                'description' => 'Supplex noir - bandes blanches verticales tronc et cuisses -
                logo UNA brodé poitrine gauche - logo "Ville de Nantes" sérigraphié cuisse droite -
                "Université Nantes Aviron" sérigraphié dos - obligatoire pour la compétition',
                'available_sizes' => 'S - M - L - XL - XXL',
                'price' => 45
            ],
            [
                'category_id' => config('e-shop.article_category_key.textile'),
                'order_type_id' => config('e-shop.order_type_key.in-stock'),
                'title' => 'Haut synthétique long',
                'description' => 'Supplex noir - Bandes blanches verticales bras et tronc -
                Logo UNA brodé poitrine gauche',
                'available_sizes' => 'S - M - L - XL',
                'price' => 35
            ],
            [
                'category_id' => config('e-shop.article_category_key.textile'),
                'order_type_id' => config('e-shop.order_type_key.in-stock'),
                'title' => 'Gilet technique',
                'description' => 'Polaire noir et conforto blanc - fermeture éclaire col -
                logo UNA serigraphié poitrine gauche - "Université Nantes Aviron" sérigraphié dos - poche à clé dos',
                'available_sizes' => 'S - M - L - XL',
                'price' => 50
            ],
            [
                'category_id' => config('e-shop.article_category_key.textile'),
                'order_type_id' => config('e-shop.order_type_key.in-stock'),
                'title' => 'Collant synthétique',
                'description' => 'Supplex noir - bandes blanches verticales',
                'available_sizes' => 'S - M - L - XL - XXL',
                'price' => 35
            ],
            [
                'category_id' => config('e-shop.article_category_key.textile'),
                'order_type_id' => config('e-shop.order_type_key.in-stock'),
                'title' => 'Maillot synthétique Régataïades Internationales de Nantes',
                'description' => 'Maillot sublimé moulant - 92% polyester et 12% élasthanne - coupe cintrée - col rond',
                'available_sizes' => 'S - M - L - XL - XXL',
                'price' => 45
            ],
            [
                'category_id' => config('e-shop.article_category_key.textile'),
                'order_type_id' => config('e-shop.order_type_key.in-stock'),
                'title' => 'Combinaison entrainement',
                'description' => 'Supplex noir - logo UNA brodé poitrine gauche - "Université Nantes" sérigraphié dos',
                'available_sizes' => 'S - M - L - XL - XXL',
                'price' => 15
            ],
            [
                'category_id' => config('e-shop.article_category_key.goodies'),
                'order_type_id' => config('e-shop.order_type_key.in-stock'),
                'title' => 'Gobelet plastique sérigraphié',
                'description' => 'Equipez-vous de votre gobelet réutilisable, votre nouveau partenaire soirée,
                estampillé des logos du club Université Nantes Aviron (UNA) et des
                Régataïades Internationales de Nantes',
                'price' => 1
            ],
            [
                'category_id' => config('e-shop.article_category_key.goodies'),
                'order_type_id' => config('e-shop.order_type_key.in-stock'),
                'title' => 'Tour de cou porte-gobelet',
                'description' => 'Compagnon de toutes vos soirées, optez pour le tour de cou permettant de fixer
                votre gobelet UNA / Régataïades lors de ses cours moments d\'inutilité',
                'price' => 1
            ],
            [
                'category_id' => config('e-shop.article_category_key.goodies'),
                'order_type_id' => config('e-shop.order_type_key.in-stock'),
                'title' => 'Accroche ceinture porte-gobelet',
                'description' => 'Compagnon de toutes vos soirées, optez pour l\'accroche ceinture permettant de fixer
                votre gobelet UNA / Régataïades lors de ses cours moments d\'inutilité',
                'price' => 1
            ],
        ]);
    }
}
