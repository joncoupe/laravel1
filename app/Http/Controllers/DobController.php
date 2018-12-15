<?php

namespace App\Http\Controllers;

use App\DobRate;
use App\Rules\DateNotInFuture;
use App\Rules\DateWithinYear;
use App\Rules\FourDigitYear;
use Carbon\Carbon;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;

class DobController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $dobRates = DobRate::paginate(env('PER_PAGE'));

        return view('welcome', ['dobRates' => $dobRates]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'dob' =>  ['bail', 'required', new FourDigitYear, new DateNotInFuture, new DateWithinYear],
        ]);

        $errors = new MessageBag;

        $dob = Carbon::createFromFormat('Y-m-d', $request['dob'])->format('Y-m-d');

        $dobRate = DobRate::firstOrCreate(['dob' => $dob]);

        //if already set, skip
        if (empty($dobRate->rate)) {
            //do api call
            $response = $this->getDobRate($dob);
            if (!empty($response)) {
                if (!empty($response['success'])) {
                    $dobRate->rate = $response['rates'][env('FIXER_CURRENCY')];
                    $dobRate->counts++;
                } elseif (!empty($response['error'])){
                    $errors->add('api', $response['error']);
                    return redirect()->route('exrates.index');
                }
            }
        } else {
            $dobRate->counts++;
        }

        $dobRate->save();

        return redirect()->route('exrates.index')
            ->with('success','Rate found for birthday.');

    }

    public function getDobRate($date){
        $client = new Client();
        $fields = [
            'access_key' => env('FIXER_API_KEY'),
            'symbols' => env('FIXER_CURRENCY')
        ];
        $data = http_build_query($fields);

        try {
            $res = $client->request('POST', 'http://data.fixer.io/api/' . $date . "?" . $data);
        } catch (\GuzzleHttp\Exception\ConnectException $e) {
            return $message['error'] = 'Connection to service failed.';
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            // This will catch all 400 level errors.
            return $message['error'] = 'Connection to service failed.';
        }

        return json_decode($res->getBody()->getContents(), true);
    }
}
