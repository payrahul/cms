<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Services\Payment\PaymentInterface;
use App\Services\UserService;
use App\Contracts\PaymentInterface;
use DB;
class TestController extends Controller
{
    protected $payment;

    // public function __construct(PaymentInterface $payment)
    // {
    //     $this->payment = $payment;
    // }

    public function __construct(UserService $userService){
        $this->service= $userService;
    }

    public function service(){
        return $this->service->allUsers();
    }

    public function pay()
    {
        return $this->payment->pay(1000);
    }

    public function testMiddleware()
    {
        dd(1);
    }

    public function testProvider()
    {
        dd(1);
    }

    // public function employeesData(PaymentInterface $payment)
    // {

    // $payment = app()->make(PaymentInterface::class, [
    //     'method'=> $request->method
    // ]);

    // return $payment->pay(500);
        
    //    $user =  DB::table('employees as e')
    //     ->join('departments as d', 'e.department_id', '=', 'd.id')
    //     // ->where('d.name', '=', 'IT')
    //     ->whereBetween('e.age', [25, 30])
    //     ->select('e.name', 'e.age', 'd.name')
    //     ->get();

    //     dd($user);


    // }

    public function employeesData(Request $request)
    {
        // Validate input
        $request->validate([
            'payment_method' => 'required|in:stripe,paypal'
        ]);

        // Dynamically resolve based on user input
        $payment = app()->make(PaymentInterface::class, [
            'method' => $request->payment_method
        ]);

        return $payment->pay(500);
    }

    
}
