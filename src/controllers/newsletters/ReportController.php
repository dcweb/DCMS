<?php

namespace Dcweb\Dcms\Controllers\Newsletters;

use Dcweb\Dcms\Controllers\Newsletters\ViewController;

use Dcweb\Dcms\Models\Newsletters\Monitor;
use Dcweb\Dcms\Models\Newsletters\Analyse;
use Dcweb\Dcms\Models\Newsletters\Analyseresult;
use Dcweb\Dcms\Controllers\Newsletters\TransactionController;

use Dcweb\Dcms\Models\Newsletters\Content;
use Dcweb\Dcms\Models\Newsletters\Campaign;
use Dcweb\Dcms\Models\Newsletters\Newsletter;
use Dcweb\Dcms\Models\Newsletters\NewsletterSentLog;
use Dcweb\Dcms\Models\Newsletters\Settings;
use Dcweb\Dcms\Models\Subscribers\Subscribers;
use Dcweb\Dcms\Models\Subscribers\Lists;

use Dcweb\Dcms\Controllers\BaseController;

use Session;
use View;
use Input;
use DB;
use \Mandrill;
use DateTime;
use DateTimeZone;
use URL;
use Request;
use Config;
use Lang;
use App;
use Redirect;
use Carbon\Carbon;

class ReportController extends BaseController {

    public function reports(){
        $Newsletters   = DB::connection('project')->select("    SELECT `newsletter_id`
                                                                FROM `newsletters_sentlog`
                                                                WHERE
                                                                type = 'list'
                                                                AND `created_at` > ?
                                                                AND newsletter_id not in (
                                                                    SELECT newsletter_id
                                                                    FROM newsletters_analyse
                                                                    WHERE date_format(created_at,'%Y-%m-%d') = ?
                                                                )
                                                            ",array(Carbon::now()->subDays(20),Carbon::now()->format('Y-m-d')));

        foreach($Newsletters as $R){
            $T = new TransactionController();
            $T->analyse($R->newsletter_id);
        }

        return 'report finished';
    }
}

?>
