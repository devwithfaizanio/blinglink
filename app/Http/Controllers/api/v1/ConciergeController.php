<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Cache;


class ConciergeController extends Controller
{
//    public function recommendPlaces(Request $request)
//    {
//        $request->validate([
//            'experience_type' => 'required|string',
//            'budget' => 'required|string',
//            'type' => 'nullable|string',
//            'details' => 'nullable|string',
//        ]);
//
//        $prompt = "
//Suggest 5 best clubs in Dubai.
//
//User Preferences:
//Experience Type: {$request->experience_type}
//Budget: {$request->budget}
//Occasion Type: {$request->type}
//Additional Details: {$request->details}
//
//Return ONLY valid JSON in this format:
//
//{
//  \"recommendations\": [
//    {
//      \"name\": \"\",
//      \"category\": \"\",
//      \"location\": \"\",
//      \"estimated_budget_aed\": \"\",
//      \"description\": \"\",
//      \"why_recommended\": \"\",
//      \"rating_out_of_5\": \"\"
//    }
//  ]
//}
//
//Do not add any explanation. JSON only.
//";
//
//        try {
//
//            $response = \OpenAI::factory()
//                ->withApiKey(config('openai.api_key'))
//                ->withHttpClient(new \GuzzleHttp\Client([
//                    'timeout'         => 60,
//                    'connect_timeout' => 10,
//                ]))
//                ->make()
//                ->chat()
//                ->create([
//                    'model' => 'gpt-5-nano',
//                    'messages' => [
//                        [
//                            'role'    => 'system',
//                            'content' => 'You are a Dubai travel and luxury experience recommendation expert.'
//                        ],
//                        [
//                            'role'    => 'user',
//                            'content' => $prompt
//                        ]
//                    ],
//                    'response_format' => ['type' => 'json_object']
//                ]);
//
//            $result = $response->choices[0]->message->content;
//
//            return $this->success(message: 'success',data: json_decode($result, true));
//
//
//        } catch (\Exception $e) {
//            return $this->error($e->getMessage());
//        }
//    }
//    public function recommendPlaces(Request $request)
//    {
//        $request->validate([
//            'experience_type' => 'required|string',
//            'budget'          => 'required|string',
//            'type'            => 'nullable|string',
//            'details'         => 'nullable|string',
//        ]);
//
//        // Build cache key from inputs
//        $cacheKey = 'club_recommendations_' . md5(
//                $request->experience_type .
//                $request->budget .
//                $request->type .
//                $request->details
//            );
//
//        // Shorter, token-efficient prompt
//        $prompt = "Suggest 5 best clubs in Dubai matching these preferences:
//Experience: {$request->experience_type}, Budget: {$request->budget}, Occasion: {$request->type}, Details: {$request->details}
//
//Return ONLY this JSON, no explanation:
//{\"recommendations\":[{\"name\":\"\",\"category\":\"\",\"location\":\"\",\"estimated_budget_aed\":\"\",\"description\":\"\",\"why_recommended\":\"\",\"rating_out_of_5\":\"\"}]}";
//
//        try {
//            $data = Cache::remember($cacheKey, now()->addHours(24), function () use ($prompt) {
//
//                $response = \OpenAI::factory()
//                    ->withApiKey(config('openai.api_key'))
//                    ->withHttpClient(new \GuzzleHttp\Client([
//                        'timeout'         => 15,
//                        'connect_timeout' => 5,
//                    ]))
//                    ->make()
//                    ->chat()
//                    ->create([
//                        'model'           => 'gpt-4o-mini',
//                        'messages'        => [
//                            [
//                                'role'    => 'system',
//                                'content' => 'You are a Dubai travel and luxury experience recommendation expert.'
//                            ],
//                            [
//                                'role'    => 'user',
//                                'content' => $prompt
//                            ]
//                        ],
//                        'response_format' => ['type' => 'json_object'],
//                        'max_tokens'      => 800,       // limit output size
//                        'temperature'     => 0.5,       // lower = faster + more focused
//                    ]);
//
//                return json_decode($response->choices[0]->message->content, true);
//            });
//
//            return $this->success(message: 'success', data: $data);
//
//        } catch (\Exception $e) {
//            return $this->error($e->getMessage());
//        }
//    }


    public function recommendPlaces(Request $request)
    {
        $request->validate([
            'experience_type' => 'required|string',
            'budget'          => 'required|string',
            'type'            => 'nullable|string',
            'details'         => 'nullable|string',
        ]);

        $user = auth()->user();

        // Reset count if it's a new month
        $user->resetRecommendationIfNewMonth();

        // Check if user has reached their plan limit
        if ($user->hasReachedRecommendationLimit()) {
            return $this->error(
                "You have reached your monthly recommendation limit of {$user->getRecommendationLimit()} for the {$user->subscription_plan} plan. Upgrade your plan or wait until next month.",
                403
            );
        }

        // Build cache key from inputs
        $cacheKey = 'club_recommendations_' . md5(
                $request->experience_type .
                $request->budget .
                $request->type .
                $request->details
            );

        $prompt = "Suggest 5 best clubs in Dubai matching these preferences:
Experience: {$request->experience_type}, Budget: {$request->budget}, Occasion: {$request->type}, Details: {$request->details}

Return ONLY this JSON, no explanation:
{\"recommendations\":[{\"name\":\"\",\"category\":\"\",\"location\":\"\",\"estimated_budget_aed\":\"\",\"description\":\"\",\"why_recommended\":\"\",\"rating_out_of_5\":\"\"}]}";

        try {
            $data = Cache::remember($cacheKey, now()->addHours(24), function () use ($prompt) {

                $response = \OpenAI::factory()
                    ->withApiKey(config('openai.api_key'))
                    ->withHttpClient(new \GuzzleHttp\Client([
                        'timeout'         => 15,
                        'connect_timeout' => 5,
                    ]))
                    ->make()
                    ->chat()
                    ->create([
                        'model'           => 'gpt-4o-mini',
                        'messages'        => [
                            [
                                'role'    => 'system',
                                'content' => 'You are a Dubai travel and luxury experience recommendation expert.'
                            ],
                            [
                                'role'    => 'user',
                                'content' => $prompt
                            ]
                        ],
                        'response_format' => ['type' => 'json_object'],
                        'max_tokens'      => 800,
                        'temperature'     => 0.5,
                    ]);

                return json_decode($response->choices[0]->message->content, true);
            });

            // Increment user's recommendation count in DB
            $user->increment('recommendation_count');

            $used      = $user->recommendation_count;
            $limit     = $user->getRecommendationLimit();
            $remaining = max(0, $limit - $used);

            return $this->success(message: 'success', data: [
                'recommendations' => $data['recommendations'],
                'usage'           => [
                    'used'             => $used,
                    'limit'            => $limit === 999999 ? 'unlimited' : $limit,
                    'remaining'        => $limit === 999999 ? 'unlimited' : $remaining,
                    'plan'             => $user->subscription_plan,
                    'resets_on'        => now()->endOfMonth()->toDateString(),
                ],
            ]);

        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function relationshipInspiration()
    {
        try {

            $prompt = "
Give me ONE short and powerful relationship inspiration message.

Rules:
- Only 1 inspiration.
- Maximum 2 sentences.
- Emotional and meaningful.
- Do NOT return JSON.
- Do NOT add explanation.
- Just plain text.
- Make it different every time.
";

            $response = \OpenAI::factory()
                ->withApiKey(config('openai.api_key'))
                ->withHttpClient(new \GuzzleHttp\Client([
                    'timeout'         => 60,
                    'connect_timeout' => 10,
                ]))
                ->make()
                ->chat()
                ->create([
                    'model' => 'gpt-5-nano',
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'You are a relationship coach who writes deep emotional inspiration messages.'
                        ],
                        [
                            'role' => 'user',
                            'content' => $prompt
                        ]
                    ],
                ]);

            $result = trim($response->choices[0]->message->content);

            return $this->success(message: 'success',data: [
                'inspiration' => $result,
                'account_status' => auth()->user()->account_status,
            ]);


        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

}
