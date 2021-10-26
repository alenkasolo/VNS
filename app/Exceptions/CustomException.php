<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redirect;
use JetBrains\PhpStorm\Pure;
use Throwable;

abstract class CustomException extends Exception
{

    protected array $attributes;
    protected ?int $customCode;
    protected ?string $redirectToRoute = null;
    #[Pure] public function __construct(array $attributes = [], $message = "Internal Server Error", $code = 500, int $customCode = null, Throwable $previous = null) {
        $this->attributes = $attributes;
        $this->customCode = $customCode;
        parent::__construct($message, $code, $previous);
    }

    abstract protected function getLangMsg(array $attributes): string;

    public function render(Request $request): Response|JsonResponse|RedirectResponse|\never
    {
        $data = [];
        $data['message'] = $this->message;
        $data['user_res_msg'] = $this->getLangMsg($this->attributes);
        $data['code'] = $this->customCode;
        if ($request->expectsJson()) {
            return response()->json($data, $this->code);
        }
        if ($this->redirectToRoute) {
            return Redirect::route($this->redirectToRoute)->withInput($request->all())->withErrors(['user_res_msg' => $this->getLangMsg($this->attributes)]);
        }
        if ($request->isMethod('get')) {
            return abort(404);
        }
        return Redirect::back()->withInput($request->all())->withErrors(['user_res_msg' => $this->getLangMsg($this->attributes)]);
    }
}
