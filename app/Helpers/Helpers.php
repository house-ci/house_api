<?php


namespace App\Helpers;


use App\Models\Ticket;
use DateInterval;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class Helpers
{

    public static function fetchCurl(string $url, array $data, $type = 'FORM_REQUEST', $bearerToken = false)
    {
        $curl = curl_init();
        if ($type == 'JSON') {
            $options = array('Content-Type: application/json');
            if ($bearerToken) {
                array_push($options, "Authorization: Bearer $bearerToken");
            }
            $payload = json_encode($data);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $options);
        } else {
            $payload = self::hydrateFormParameters($data);
        }
        $options = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $payload,
        ];
        curl_setopt_array($curl, $options);
        $response = curl_exec($curl);
        curl_close($curl);
        if ($type == 'JSON') {
            return json_decode($response);
        }
        return $response;
    }

    public static function saveAsJson(string $path, array $payload): void
    {
        $fileName = date('Ymd.His');
        foreach ($payload as $key => $value) {
            $fileName .= $key . '.' . $value;
        }
        Storage::disk($path)->put($fileName . '.json', response()->json($payload));
    }


    static function filterEngine($options, $OrderByOptions = [])
    {
        try {

            $filters = [];
            //Limit
            $defaultLimit = 100;
            empty($options['limit']) ? $filters['limit'] = $defaultLimit : $filters['limit'] = $options['limit'];

            //orderBy
            $defaultOrderBy = 'created_at';
            $filters['orderBy'] = $defaultOrderBy;

            if (!empty($options['orderBy'])
                && array_key_exists($options['orderBy'], $OrderByOptions)) {
                $filters['orderBy'] = $OrderByOptions[$options['orderBy']];
            }

            //order
            $defaultOrders = ['desc', 'asc'];
            $filters['order'] = $defaultOrders[0];
            if (!empty($options['order']) && in_array($options['order'], $defaultOrders)) {
                $filters['order'] = $options['order'];
            }
            return $filters;
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public static function validation(Request $request, array $rules)
    {
        $validator = Validator::make($request->all(), $rules);
        $errors = null;
        if (count($validator->messages()) != 0) {
            $errors = $validator->messages();
        }
        return $errors;
    }

    static function generateTicketId($length = 12)
    {
        $numbers = '0123456789';
        $letters = 'abcdefghijklmnopqrstuvwxyz';
        $capitals = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numbersLength = strlen($numbers);
        $lettersLength = strlen($letters);
        $capitalsLength = strlen($capitals);
        $randomString = '';
        $numberOfAlphaCharacters = $length - 1;
        for ($i = 0; $i < $numberOfAlphaCharacters; $i++) {
            $randomString .= $capitals[rand(0, $capitalsLength - 1)];
        }
        for ($i = 0; $i < 1; $i++) {
            $randomString .= $numbers[rand(0, $numbersLength - 1)];
        }
        $ticket = Ticket::where('num_ticket', $randomString)->first();
        if (!empty($ticket)) {
            self::generateTicketId();
        }
        return $randomString;
    }

    static function calculateGains($amount, $quote)
    {
        return $amount * $quote;
    }

    /*
 * Inserts a new key/value before the key in the array.
 *
 * @param $key
 *   The key to insert before.
 * @param $array
 *   An array to insert in to.
 * @param $new_key
 *   The key to insert.
 * @param $new_value
 *   An value to insert.
 *
 * @return
 *   The new array if the key exists, FALSE otherwise.
 *
 * @see array_insert_after()
 */
    public static function array_insert_before($key, array &$array, $new_key, $new_value)
    {
        if (array_key_exists($key, $array)) {
            $new = array();
            foreach ($array as $k => $value) {
                if ($k === $key) {
                    $new[$new_key] = $new_value;
                }
                $new[$k] = $value;
            }
            return $new;
        }
        return FALSE;
    }

    /*
     * Inserts a new key/value after the key in the array.
     *
     * @param $key
     *   The key to insert after.
     * @param $array
     *   An array to insert in to.
     * @param $new_key
     *   The key to insert.
     * @param $new_value
     *   An value to insert.
     *
     * @return
     *   The new array if the key exists, FALSE otherwise.
     *
     * @see array_insert_before()
     */
    public static function array_insert_after($key, array &$array, $new_key, $new_value)
    {
        if (array_key_exists($key, $array)) {
            $new = array();
            foreach ($array as $k => $value) {
                $new[$k] = $value;
                if ($k === $key) {
                    $new[$new_key] = $new_value;
                }
            }
            return $new;
        }
        return FALSE;
    }

    public static function hydrateFormParameters(array $items): string
    {
        $params = '';
        foreach ($items as $key => $item) {
            $params .= "$key=$item&";
        }
        return substr($params, 0, -1);
    }

    public static function getDayOfMonth($day, $start_date, $end_date,$nbrMonth=1)
    {
        $dates = array();
        $current_date = strtotime((strlen($day) === 1 ? '0' . $day : $day) . '-' . date('m-Y', strtotime($start_date)));
        $end_date = strtotime($end_date);
        while ($current_date <= $end_date) {
            $dates[] = date('d-m-Y', $current_date);
            $current_date = strtotime('+'.$nbrMonth.' month', $current_date);
        }
        return $dates;
    }

    public static function subtractMonth($date,$nbrMonth=1)
    {
//        $dateTime = new DateTime($date); // create a DateTime object with the specific date and time
        $interval = new DateInterval('P'.$nbrMonth.'M'); // create a DateInterval object representing one month// subtract one month from the specific date and time
        return  (new DateTime($date))->sub($interval)->format('d-m-Y');
    }
    public static function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }

   public static function roundUpTo($number, $multiple)
    {
        if ($multiple == 0) {
            return $number;
        }

        $remainder = $number % $multiple;

        if ($remainder == 0) {
            return $number;
        }

        return $number + ($multiple - $remainder);
    }
}
