<?php

use Illuminate\Database\Seeder;

class EShopArticlesTableSeeder extends Seeder
{
    public function run()
    {
        $e_shop_article_repo = app(\App\Repositories\EShop\EShopArticleRepositoryInterface::class);

        $e_shop_article_repo->createMultiple([
            [
                'category_id' => config('e-shop.article_category_key.textile'),
                'availability_type_id' => config('e-shop.availability_type_key.in-stock'),
                'title' => 'Combinaison règlementaire',
                'description' => 'Supplex noir - bandes blanches verticales tronc et cuisses -
                logo UNA brodé poitrine gauche - logo "Ville de Nantes" sérigraphié cuisse droite -
                "Université Nantes Aviron" sérigraphié dos - obligatoire pour la compétition',
                'size_s' => 1,
                'size_m' => 1,
                'size_l' => 1,
                'size_xl' => 1,
                'size_xxl' => 1,
                'price' => 44.95
            ],
            [
                'category_id' => config('e-shop.article_category_key.textile'),
                'availability_type_id' => config('e-shop.availability_type_key.depleted'),
                'title' => 'Haut synthétique long',
                'description' => 'Supplex noir - Bandes blanches verticales bras et tronc -
                Logo UNA brodé poitrine gauche',
                'size_s' => 1,
                'size_m' => 1,
                'size_l' => 1,
                'size_xl' => 1,
                'price' => 34.95
            ],
            [
                'category_id' => config('e-shop.article_category_key.textile'),
                'availability_type_id' => config('e-shop.availability_type_key.on-order'),
                'title' => 'Gilet technique',
                'description' => 'Polaire noir et conforto blanc - fermeture éclaire col -
                logo UNA serigraphié poitrine gauche - "Université Nantes Aviron" sérigraphié dos - poche à clé dos',
                'size_s' => 1,
                'size_m' => 1,
                'size_l' => 1,
                'size_xl' => 1,
                'price' => 49.95
            ],
            [
                'category_id' => config('e-shop.article_category_key.textile'),
                'availability_type_id' => config('e-shop.availability_type_key.in-stock'),
                'title' => 'Collant synthétique',
                'description' => 'Supplex noir - bandes blanches verticales',
                'size_s' => 1,
                'size_m' => 1,
                'size_l' => 1,
                'size_xl' => 1,
                'size_xxl' => 1,
                'price' => 34.95
            ],
            [
                'category_id' => config('e-shop.article_category_key.textile'),
                'availability_type_id' => config('e-shop.availability_type_key.in-stock'),
                'title' => 'Maillot synthétique Régataïades Internationales de Nantes',
                'description' => 'Maillot sublimé moulant - 92% polyester et 12% élasthanne - coupe cintrée - col rond',
                'size_s' => 1,
                'size_m' => 1,
                'size_l' => 1,
                'size_xl' => 1,
                'size_xxl' => 1,
                'price' => 29.95
            ],
            [
                'category_id' => config('e-shop.article_category_key.textile'),
                'availability_type_id' => config('e-shop.availability_type_key.in-stock'),
                'title' => 'Combinaison entrainement',
                'description' => 'Supplex noir - logo UNA brodé poitrine gauche - "Université Nantes" sérigraphié dos',
                'size_s' => 1,
                'size_m' => 1,
                'size_l' => 1,
                'size_xl' => 1,
                'size_xxl' => 1,
                'price' => 14.95
            ],
            [
                'category_id' => config('e-shop.article_category_key.goodies'),
                'availability_type_id' => config('e-shop.availability_type_key.in-stock'),
                'title' => 'Gobelet plastique sérigraphié',
                'description' => 'Equipez-vous de votre gobelet réutilisable, votre nouveau partenaire soirée,
                estampillé des logos du club Université Nantes Aviron (UNA) et des
                Régataïades Internationales de Nantes',
                'price' => 0.95
            ],
            [
                'category_id' => config('e-shop.article_category_key.goodies'),
                'availability_type_id' => config('e-shop.availability_type_key.in-stock'),
                'title' => 'Tour de cou porte-gobelet',
                'description' => 'Compagnon de toutes vos soirées, optez pour le tour de cou permettant de fixer
                votre gobelet UNA / Régataïades lors de ses cours moments d\'inutilité',
                'price' => 0.95
            ],
            [
                'category_id' => config('e-shop.article_category_key.goodies'),
                'availability_type_id' => config('e-shop.availability_type_key.in-stock'),
                'title' => 'Accroche ceinture porte-gobelet',
                'description' => 'Compagnon de toutes vos soirées, optez pour l\'accroche ceinture permettant de fixer
                votre gobelet UNA / Régataïades lors de ses cours moments d\'inutilité',
                'price' => 0.95
            ],
        ]);
    }
}
