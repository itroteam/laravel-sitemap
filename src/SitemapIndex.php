<?php

namespace Itroteam\Sitemap;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Itroteam\Sitemap\Tags\Sitemap;
use Itroteam\Sitemap\Tags\Tag;

class SitemapIndex implements Responsable, Renderable
{
    /** @var \Itroteam\Sitemap\Tags\Sitemap[] */
    protected array $tags = [];
    protected string $stylesheet = '';

    public static function create(): static
    {
        return new static();
    }

    public function setStylsheet(string $stylesheet): static
    {
        $this->stylesheet = $stylesheet;
        return $this;
    }

    public function add(string | Sitemap $tag): static
    {
        if (is_string($tag)) {
            $tag = Sitemap::create($tag);
        }

        $this->tags[] = $tag;

        return $this;
    }

    public function getSitemap(string $url): ?Sitemap
    {
        return collect($this->tags)->first(function (Tag $tag) use ($url) {
            return $tag->getType() === 'sitemap' && $tag->url === $url;
        });
    }

    public function hasSitemap(string $url): bool
    {
        return (bool) $this->getSitemap($url);
    }

    public function render(): string
    {
        $tags = $this->tags;
        $stylesheet = $this->stylesheet;

        return view('sitemap::sitemapIndex/index')
            ->with(compact('tags', 'stylesheet'))
            ->render();
    }

    public function writeToFile(string $path): static
    {
        file_put_contents($path, $this->render());

        return $this;
    }

    public function writeToDisk(string $disk, string $path, bool $public = false): static
    {
        $visibility = ($public) ? 'public' : 'private';

        Storage::disk($disk)->put($path, $this->render(), $visibility);

        return $this;
    }

    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        return Response::make($this->render(), 200, [
            'Content-Type' => 'text/xml',
        ]);
    }
}
