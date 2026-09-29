<?php

namespace App\Http\Middleware;

use App\Models\Visitor;
use Closure;
use Illuminate\Http\Request;

class TrackVisitor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $this->recordVisit($request);

        return $next($request);
    }

    protected function recordVisit(Request $request): void
    {
        $today = now()->toDateString();
        $ip = $request->ip();

        // Hindari double-count kalau IP yang sama sudah tercatat hari ini
        $alreadyVisited = Visitor::where('ip_address', $ip)
            ->where('visited_date', $today)
            ->exists();

        if ($alreadyVisited) {
            return;
        }

        Visitor::create([
            'ip_address' => $ip,
            'session_id' => session()->getId(),
            'url' => $request->path(),
            'user_agent' => $request->userAgent(),
            'visited_date' => $today,
        ]);
    }
}
