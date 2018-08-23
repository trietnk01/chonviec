<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SitemapRender extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:auto';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto create sitemap.xml';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $sitemap = \App::make("sitemap");        
        $categories_article=\DB::table('category_article')->orderBy('created_at', 'desc')->select('alias','updated_at')->get();
        $categories_product=\DB::table('category_product')->orderBy('created_at', 'desc')->select('alias','updated_at')->get();
        $articles = \DB::table('article')->orderBy('created_at', 'desc')->select('alias','updated_at')->get();
        $products = \DB::table('product')->orderBy('created_at', 'desc')->select('alias','updated_at')->get();
        $projects = \DB::table('project')->orderBy('created_at', 'desc')->select('alias','updated_at')->get();
        $organizations = \DB::table('organization')->orderBy('created_at', 'desc')->select('alias','updated_at')->get();
        $project_articles = \DB::table('project_article')->orderBy('created_at', 'desc')->select('alias','updated_at')->get();
        if(count($categories_article) > 0){
            foreach ($categories_article as $category_article){
                $sitemap->add(url($category_article->alias . '.html') , $category_article->updated_at, 0.9, 'monthly'); //daily
            }
        }       
        if(count($categories_product) > 0){
            foreach ($categories_product as $category_product){
                $sitemap->add(url($category_product->alias . '.html') , $category_product->updated_at, 0.9, 'monthly'); //daily
            }
        }
        if(count($articles) > 0){
            foreach ($articles as $article){
                $sitemap->add(url($article->alias . '.html') , $article->updated_at, 0.9, 'monthly'); //daily
            }
        }       
        if(count($products) > 0){
            foreach ($products as $product){
                $sitemap->add(url($product->alias . '.html') , $product->updated_at, 0.9, 'monthly'); //daily
            }
        }       
        if(count($projects) > 0){
            foreach ($projects as $project){
                $sitemap->add(url($project->alias . '.html') , $project->updated_at, 0.9, 'monthly'); //daily
            }
        }       
        if(count($organizations) > 0){
            foreach ($organizations as $organization){
                $sitemap->add(url($organization->alias . '.html') , $organization->updated_at, 0.9, 'monthly'); //daily
            }
        }       
        if(count($project_articles) > 0){
            foreach ($project_articles as $project_article){
                $sitemap->add(url($project_article->alias . '.html') , $project_article->updated_at, 0.9, 'monthly'); //daily
            }
        }             
        $sitemap->store('xml', 'sitemap');
        @copy(base_path('public'.DS.'sitemap.xml'),base_path('sitemap.xml'));
        unlink(base_path('public'.DS.'sitemap.xml'));
        $fp=fopen(base_path('robots.txt'), 'w');        
        $content=
        'User-agent: *
        Disallow: /app/
        Disallow: /bootstrap/
        Disallow: /config/
        Disallow: /database/
        Disallow: /document/
        Disallow: /public/
        Disallow: /resources/
        Disallow: /routes/
        Disallow: /storage/
        Disallow: /tests/
        Disallow: /upload/
        Disallow: /vendor/
        Disallow: /.env
        Sitemap: '.url("sitemap.xml");
        fwrite($fp, $content);
        fclose($fp);
    }
}
