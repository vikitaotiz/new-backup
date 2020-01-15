<?php

namespace App\Http\Controllers;

use App\User;
use App\Timing;
use App\DayBreak;
use App\Availability;
use Illuminate\Http\Request;

class TimingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate form data
        $request->validate([
            'monday_opening' => 'nullable|date_format:H:i',
            'tuesday_opening' => 'nullable|date_format:H:i',
            'wednesday_opening' => 'nullable|date_format:H:i',
            'thursday_opening' => 'nullable|date_format:H:i',
            'friday_opening' => 'nullable|date_format:H:i',
            'saturday_opening' => 'nullable|date_format:H:i',
            'sunday_opening' => 'nullable|date_format:H:i',
            /*'sunday_from.*' => 'nullable|date_format:H:i',
            'monday_from.*' => 'nullable|date_format:H:i',
            'tuesday_from.*' => 'nullable|date_format:H:i',
            'wednesday_from.*' => 'nullable|date_format:H:i',
            'thursday_from.*' => 'nullable|date_format:H:i',
            'friday_from.*' => 'nullable|date_format:H:i',
            'saturday_from.*' => 'nullable|date_format:H:i',*/
            'monday_closing' => 'nullable|date_format:H:i|after:monday_opening',
            'tuesday_closing' => 'nullable|date_format:H:i|after:tuesday_opening',
            'wednesday_closing' => 'nullable|date_format:H:i|after:wednesday_opening',
            'thursday_closing' => 'nullable|date_format:H:i|after:thursday_opening',
            'friday_closing' => 'nullable|date_format:H:i|after:friday_opening',
            'saturday_closing' => 'nullable|date_format:H:i|after:saturday_opening',
            'sunday_closing' => 'nullable|date_format:H:i|after:sunday_opening',
            /*'sunday_to.*' => 'nullable|date_format:H:i|after:sunday_from',
            'monday_to.*' => 'nullable|date_format:H:i|after:monday_from',
            'tuesday_to.*' => 'nullable|date_format:H:i|after:tuesday_from',
            'wednesday_to.*' => 'nullable|date_format:H:i|after:wednesday_from',
            'thursday_to.*' => 'nullable|date_format:H:i|after:thursday_from',
            'friday_to.*' => 'nullable|date_format:H:i|after:friday_from',
            'saturday_to.*' => 'nullable|date_format:H:i|after:saturday_from',*/
        ]);

        // Check if timing exists
        $user = User::find($request->id);
        if (count($user->timing)) {
            foreach ($user->timing as $item) {
                $services = null;

                switch ($item->day) {
                    case "1":
                        if (isset($request->status_sunday)) {
                            $status = 1;
                        } else {
                            $status = 0;
                        }

                        if (isset($request->sunday_services)) {
                            $services = implode (", ", $request->sunday_services);
                        }

                        $day = 1;
                        $from = $request->sunday_opening ? date('H:i', strtotime($request->sunday_opening)) : null;
                        $to = $request->sunday_closing ? date('H:i', strtotime($request->sunday_closing)) : null;
                        break;
                    case "2":
                        if (isset($request->status_monday)) {
                            $status = 1;
                        } else {
                            $status = 0;
                        }

                        if (isset($request->monday_services)) {
                            $services = implode (", ", $request->monday_services);
                        }

                        $day = 2;
                        $from = $request->monday_opening ? date('H:i', strtotime($request->monday_opening)) : null;
                        $to = $request->monday_closing ? date('H:i', strtotime($request->monday_closing)) : null;
                        break;
                    case "3":
                        if (isset($request->status_tuesday)) {
                            $status = 1;
                        } else {
                            $status = 0;
                        }

                        if (isset($request->tuesday_services)) {
                            $services = implode (", ", $request->tuesday_services);
                        }

                        $day = 3;
                        $from = $request->tuesday_opening ? date('H:i', strtotime($request->tuesday_opening)) : null;
                        $to = $request->tuesday_closing ? date('H:i', strtotime($request->tuesday_closing)) : null;
                        break;
                    case "4":
                        if (isset($request->status_wednesday)) {
                            $status = 1;
                        } else {
                            $status = 0;
                        }

                        if (isset($request->wednesday_services)) {
                            $services = implode (", ", $request->wednesday_services);
                        }

                        $day = 4;
                        $from = $request->wednesday_opening ? date('H:i', strtotime($request->wednesday_opening)) : null;
                        $to = $request->wednesday_closing ? date('H:i', strtotime($request->wednesday_closing)) : null;
                        break;
                    case "5":
                        if (isset($request->status_thursday)) {
                            $status = 1;
                        } else {
                            $status = 0;
                        }

                        if (isset($request->thursday_services)) {
                            $services = implode (", ", $request->thursday_services);
                        }

                        $day = 5;
                        $from = $request->thursday_opening ? date('H:i', strtotime($request->thursday_opening)) : null;
                        $to = $request->thursday_closing ? date('H:i', strtotime($request->thursday_closing)) : null;
                        break;
                    case "6":
                        if (isset($request->status_friday)) {
                            $status = 1;
                        } else {
                            $status = 0;
                        }

                        if (isset($request->friday_services)) {
                            $services = implode (", ", $request->friday_services);
                        }

                        $day = 6;
                        $from = $request->friday_opening ? date('H:i', strtotime($request->friday_opening)) : null;
                        $to = $request->friday_closing ? date('H:i', strtotime($request->friday_closing)) : null;
                        break;
                    default:
                        if (isset($request->status_saturday)) {
                            $status = 1;
                        } else {
                            $status = 0;
                        }

                        if (isset($request->saturday_services)) {
                            $services = implode (", ", $request->saturday_services);
                        }

                        $day = 7;
                        $from = $request->saturday_opening ? date('H:i', strtotime($request->saturday_opening)) : null;
                        $to = $request->saturday_closing ? date('H:i', strtotime($request->saturday_closing)) : null;
                }

                $timing = Timing::find($item->id);
                $timing->from = $from;
                $timing->to = $to;
                $timing->day = $day;
                $timing->status = $status;
                $timing->services = $services ?? null;
                $timing->user_id = $request->id;
                $timing->creator_id = auth()->id();
                $timing->save();

                // Sunday break
                if ($item->day == 1) {
                    if (isset($request->sunday_from)) {
                        foreach ($request->sunday_from as $index => $value) {
                            if ($value != null && $request->sunday_to[$index] != null) {
                                $break = new DayBreak();
                                $break->from = $value;
                                $break->to = $request->sunday_to[$index];
                                $break->timing_id = $timing->id;
                                $break->creator_id = auth()->id();
                                $break->save();
                            }
                        }
                    }

                    if (isset($request->sunday_update_from)) {
                        foreach ($request->sunday_update_from as $index => $value) {
                            if ($value != null && $request->sunday_update_to[$index] != null) {
                                $break = DayBreak::find($request->sunday_update_id[$index]);
                                $break->from = $value;
                                $break->to = $request->sunday_update_to[$index];
                                $break->timing_id = $timing->id;
                                $break->creator_id = auth()->id();
                                $break->save();
                            }
                        }
                    }
                }

                // Monday break
                if ($item->day == 2) {
                    if (isset($request->monday_from)) {
                        foreach ($request->monday_from as $index => $value) {
                            if ($value != null && $request->monday_to[$index] != null) {
                                $break = new DayBreak();
                                $break->from = $value;
                                $break->to = $request->monday_to[$index];
                                $break->timing_id = $timing->id;
                                $break->creator_id = auth()->id();
                                $break->save();
                            }
                        }
                    }

                    if (isset($request->monday_update_from)) {
                        foreach ($request->monday_update_from as $index => $value) {
                            if ($value != null && $request->monday_update_to[$index] != null) {
                                $break = DayBreak::find($request->monday_update_id[$index]);
                                $break->from = $value;
                                $break->to = $request->monday_update_to[$index];
                                $break->timing_id = $timing->id;
                                $break->creator_id = auth()->id();
                                $break->save();
                            }
                        }
                    }
                }

                // Tuesday break
                if ($item->day == 3) {
                    if (isset($request->tuesday_from)) {
                        foreach ($request->tuesday_from as $index => $value) {
                            if ($value != null && $request->tuesday_to[$index] != null) {
                                $break = new DayBreak();
                                $break->from = $value;
                                $break->to = $request->tuesday_to[$index];
                                $break->timing_id = $timing->id;
                                $break->creator_id = auth()->id();
                                $break->save();
                            }
                        }
                    }

                    if (isset($request->tuesday_update_from)) {
                        foreach ($request->tuesday_update_from as $index => $value) {
                            if ($value != null && $request->tuesday_update_to[$index] != null) {
                                $break = DayBreak::find($request->tuesday_update_id[$index]);
                                $break->from = $value;
                                $break->to = $request->tuesday_update_to[$index];
                                $break->timing_id = $timing->id;
                                $break->creator_id = auth()->id();
                                $break->save();
                            }
                        }
                    }
                }

                // Wednesday break
                if ($item->day == 4) {
                    if (isset($request->wednesday_from)) {
                        foreach ($request->wednesday_from as $index => $value) {
                            if ($value != null && $request->wednesday_to[$index] != null) {
                                $break = new DayBreak();
                                $break->from = $value;
                                $break->to = $request->wednesday_to[$index];
                                $break->timing_id = $timing->id;
                                $break->creator_id = auth()->id();
                                $break->save();
                            }
                        }
                    }

                    if (isset($request->wednesday_update_from)) {
                        foreach ($request->wednesday_update_from as $index => $value) {
                            if ($value != null && $request->wednesday_update_to[$index] != null) {
                                $break = DayBreak::find($request->wednesday_update_id[$index]);
                                $break->from = $value;
                                $break->to = $request->wednesday_update_to[$index];
                                $break->timing_id = $timing->id;
                                $break->creator_id = auth()->id();
                                $break->save();
                            }
                        }
                    }
                }

                // Thursday break
                if ($item->day == 5) {
                    if (isset($request->thursday_from)) {
                        foreach ($request->thursday_from as $index => $value) {
                            if ($value != null && $request->thursday_to[$index] != null) {
                                $break = new DayBreak();
                                $break->from = $value;
                                $break->to = $request->thursday_to[$index];
                                $break->timing_id = $timing->id;
                                $break->creator_id = auth()->id();
                                $break->save();
                            }
                        }
                    }

                    if (isset($request->thursday_update_from)) {
                        foreach ($request->thursday_update_from as $index => $value) {
                            if ($value != null && $request->thursday_update_to[$index] != null) {
                                $break = DayBreak::find($request->thursday_update_id[$index]);
                                $break->from = $value;
                                $break->to = $request->thursday_update_to[$index];
                                $break->timing_id = $timing->id;
                                $break->creator_id = auth()->id();
                                $break->save();
                            }
                        }
                    }
                }

                // Friday break
                if ($item->day == 6) {
                    if (isset($request->friday_from)) {
                        foreach ($request->friday_from as $index => $value) {
                            if ($value != null && $request->friday_to[$index] != null) {
                                $break = new DayBreak();
                                $break->from = $value;
                                $break->to = $request->friday_to[$index];
                                $break->timing_id = $timing->id;
                                $break->creator_id = auth()->id();
                                $break->save();
                            }
                        }
                    }

                    if (isset($request->friday_update_from)) {
                        foreach ($request->friday_update_from as $index => $value) {
                            if ($value != null && $request->friday_update_to[$index] != null) {
                                $break = DayBreak::find($request->friday_update_id[$index]);
                                $break->from = $value;
                                $break->to = $request->friday_update_to[$index];
                                $break->timing_id = $timing->id;
                                $break->creator_id = auth()->id();
                                $break->save();
                            }
                        }
                    }
                }

                // Saturday break
                if ($item->day == 7) {
                    if (isset($request->saturday_from)) {
                        foreach ($request->saturday_from as $index => $value) {
                            if ($value != null && $request->saturday_to[$index] != null) {
                                $break = new DayBreak();
                                $break->from = $value;
                                $break->to = $request->saturday_to[$index];
                                $break->timing_id = $timing->id;
                                $break->creator_id = auth()->id();
                                $break->save();
                            }
                        }
                    }

                    if (isset($request->saturday_update_from)) {
                        foreach ($request->saturday_update_from as $index => $value) {
                            if ($value != null && $request->saturday_update_to[$index] != null) {
                                $break = DayBreak::find($request->saturday_update_id[$index]);
                                $break->from = $value;
                                $break->to = $request->saturday_update_to[$index];
                                $break->timing_id = $timing->id;
                                $break->creator_id = auth()->id();
                                $break->save();
                            }
                        }
                    }
                }
            }
        } else {
            for ($i = 1; $i < 8; $i ++) {
                $services = null;

                switch ($i) {
                    case "1":
                        if (isset($request->status_sunday)) {
                            $status = 1;
                        } else {
                            $status = 0;
                        }

                        if (isset($request->sunday_services)) {
                            $services = implode (", ", $request->sunday_services);
                        }

                        $day = 1;
                        $from = $request->sunday_opening ? date('H:i', strtotime($request->sunday_opening)) : null;
                        $to = $request->sunday_closing ? date('H:i', strtotime($request->sunday_closing)) : null;
                        break;
                    case "2":
                        if (isset($request->status_monday)) {
                            $status = 1;
                        } else {
                            $status = 0;
                        }

                        if (isset($request->monday_services)) {
                            $services = implode (", ", $request->monday_services);
                        }

                        $day = 2;
                        $from = $request->monday_opening ? date('H:i', strtotime($request->monday_opening)) : null;
                        $to = $request->monday_closing ? date('H:i', strtotime($request->monday_closing)) : null;
                        break;
                    case "3":
                        if (isset($request->status_tuesday)) {
                            $status = 1;
                        } else {
                            $status = 0;
                        }

                        if (isset($request->tuesday_services)) {
                            $services = implode (", ", $request->tuesday_services);
                        }

                        $day = 3;
                        $from = $request->tuesday_opening ? date('H:i', strtotime($request->tuesday_opening)) : null;
                        $to = $request->tuesday_closing ? date('H:i', strtotime($request->tuesday_closing)) : null;
                        break;
                    case "4":
                        if (isset($request->status_wednesday)) {
                            $status = 1;
                        } else {
                            $status = 0;
                        }

                        if (isset($request->wednesday_services)) {
                            $services = implode (", ", $request->wednesday_services);
                        }

                        $day = 4;
                        $from = $request->wednesday_opening ? date('H:i', strtotime($request->wednesday_opening)) : null;
                        $to = $request->wednesday_closing ? date('H:i', strtotime($request->wednesday_closing)) : null;
                        break;
                    case "5":
                        if (isset($request->status_thursday)) {
                            $status = 1;
                        } else {
                            $status = 0;
                        }

                        if (isset($request->thursday_services)) {
                            $services = implode (", ", $request->thursday_services);
                        }

                        $day = 5;
                        $from = $request->thursday_opening ? date('H:i', strtotime($request->thursday_opening)) : null;
                        $to = $request->thursday_closing ? date('H:i', strtotime($request->thursday_closing)) : null;
                        break;
                    case "6":
                        if (isset($request->status_friday)) {
                            $status = 1;
                        } else {
                            $status = 0;
                        }

                        if (isset($request->friday_services)) {
                            $services = implode (", ", $request->friday_services);
                        }

                        $day = 6;
                        $from = $request->friday_opening ? date('H:i', strtotime($request->friday_opening)) : null;
                        $to = $request->friday_closing ? date('H:i', strtotime($request->friday_closing)) : null;
                        break;
                    default:
                        if (isset($request->status_saturday)) {
                            $status = 1;
                        } else {
                            $status = 0;
                        }

                        if (isset($request->saturday_services)) {
                            $services = implode (", ", $request->saturday_services);
                        }

                        $day = 7;
                        $from = $request->saturday_opening ? date('H:i', strtotime($request->saturday_opening)) : null;
                        $to = $request->saturday_closing ? date('H:i', strtotime($request->saturday_closing)) : null;
                }

                $timing = new Timing();
                $timing->from = $from;
                $timing->to = $to;
                $timing->day = $day;
                $timing->status = $status;
                $timing->services = $services ?? null;
                $timing->user_id = $request->id;
                $timing->creator_id = auth()->id();
                $timing->save();

                // Sunday break
                if ($i == 1 && isset($request->sunday_from)) {
                    foreach ($request->sunday_from as $index => $value) {
                        if ($value != null && $request->sunday_to[$index] != null) {
                            $break = new DayBreak();
                            $break->from = $value;
                            $break->to = $request->sunday_to[$index];
                            $break->timing_id = $timing->id;
                            $break->creator_id = auth()->id();
                            $break->save();
                        }
                    }
                }

                // Monday break
                if ($i == 2 && isset($request->monday_from)) {
                    foreach ($request->monday_from as $index => $value) {
                        if ($value != null && $request->monday_to[$index] != null) {
                            $break = new DayBreak();
                            $break->from = $value;
                            $break->to = $request->monday_to[$index];
                            $break->timing_id = $timing->id;
                            $break->creator_id = auth()->id();
                            $break->save();
                        }
                    }
                }

                // Tuesday break
                if ($i == 3 && isset($request->tuesday_from)) {
                    foreach ($request->tuesday_from as $index => $value) {
                        if ($value != null && $request->tuesday_to[$index] != null) {
                            $break = new DayBreak();
                            $break->from = $value;
                            $break->to = $request->tuesday_to[$index];
                            $break->timing_id = $timing->id;
                            $break->creator_id = auth()->id();
                            $break->save();
                        }
                    }
                }

                // Wednesday break
                if ($i == 4 && isset($request->wednesday_from)) {
                    foreach ($request->wednesday_from as $index => $value) {
                        if ($value != null && $request->wednesday_to[$index] != null) {
                            $break = new DayBreak();
                            $break->from = $value;
                            $break->to = $request->wednesday_to[$index];
                            $break->timing_id = $timing->id;
                            $break->creator_id = auth()->id();
                            $break->save();
                        }
                    }
                }

                // Thursday break
                if ($i == 5 && isset($request->thursday_from)) {
                    foreach ($request->thursday_from as $index => $value) {
                        if ($value != null && $request->thursday_to[$index] != null) {
                            $break = new DayBreak();
                            $break->from = $value;
                            $break->to = $request->thursday_to[$index];
                            $break->timing_id = $timing->id;
                            $break->creator_id = auth()->id();
                            $break->save();
                        }
                    }
                }

                // Friday break
                if ($i == 6 && isset($request->friday_from)) {
                    foreach ($request->friday_from as $index => $value) {
                        if ($value != null && $request->friday_to[$index] != null) {
                            $break = new DayBreak();
                            $break->from = $value;
                            $break->to = $request->friday_to[$index];
                            $break->timing_id = $timing->id;
                            $break->creator_id = auth()->id();
                            $break->save();
                        }
                    }
                }

                // Saturday break
                if ($i == 7 && isset($request->saturday_from)) {
                    foreach ($request->saturday_from as $index => $value) {
                        if ($value != null && $request->saturday_to[$index] != null) {
                            $break = new DayBreak();
                            $break->from = $value;
                            $break->to = $request->saturday_to[$index];
                            $break->timing_id = $timing->id;
                            $break->creator_id = auth()->id();
                            $break->save();
                        }
                    }
                }
            }
        }

        return redirect()->back()->with('success', 'Timings updated successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request)
    {
        // Validate form data
        $request->validate([
            'doctor_id' => 'required|integer',
            'service_id' => 'required|integer',
            'month' => 'required|integer',
            'year' => 'required|integer',
            'days' => 'required|integer',
            'timeZone' => 'required|string',
        ]);

        // Get timings
        $timings = [];
        $doctor = User::find($request->doctor_id);

        // Set timing slot
        if ($doctor->slot != null){
            $slot = $doctor->slot * 60;
        } else {
            $slot = 900;
        }

        // Get date time from request
        $month = $request->month;
        $year = $request->year;

        // Get current month dates
        for($d = 1; $d <= $request->days; $d++) {
            $time = mktime(0, 0, 0, $month, $d, $year);
            if (date('m', $time) == $month) {
                $date = date('Y-m-d H:i:s', $time);
            }

            // Get week day and date
            $jd = gregoriantojd(date('m', strtotime($date)), date('d', strtotime($date)), date('Y', strtotime($date)));
            $day = jddayofweek($jd) + 1;
            $timing = Timing::where(['user_id' => $request->doctor_id, 'day' => $day])->first();
            $intervals = [];

            // Check if doctor timing is set & not null
            if ($timing && $timing->status && $timing->from && $timing->to) {
                // Get unavailability
                $unavailabilities = Availability::where(['user_id' => $request->doctor_id, 'type' => 0, ['from', '<=', $date], ['to', '>=', $date]])->first();

                if ($unavailabilities || !in_array($request->service_id, explode(', ', $timing->services))) {
                    continue;
                }

                // Get doctor opening & closing time
                $open_time = strtotime($timing->from);
                $close_time = strtotime($timing->to);

                // Check if doctor has timing break
                if (count($timing->break)) {
                    $breaks = [];

                    // Keep break from & to in an array
                    foreach ($timing->break as $break) {
                        $breaks[] = strtotime($break->from);
                        $breaks[] = strtotime($break->to);
                    }

                    // Loop over each array i.e: breaks
                    for ($i = 0; $i <= count($timing->break); $i++) {
                        // Check if date is today
                        if (date('Y-m-d') == date('Y-m-d', strtotime($date))) {
                            $today = new \DateTime("now", new \DateTimeZone($request->timeZone) );
                            $now = $today->format("H:i:s");

                            if ($now > $timing->from && $now < $timing->to) {
                                // If break is at first index loop starts at open time
                                if ($i == 0) {
                                    for($j = $open_time; $j < $breaks[$i]; $j += $slot) {
                                        if ($j >= strtotime($now)) {
                                            $intervals[] = date("h:iA", $j);
                                        }
                                    }
                                } else {
                                    // If last index then loop ends at closing time
                                    if ($i == count($timing->break)) {
                                        for($j = $breaks[count($breaks) - 1]; $j < $close_time; $j += $slot) {
                                            if ($j >= strtotime($now)) {
                                                $intervals[] = date("h:iA", $j);
                                            }
                                        }
                                    } else {
                                        for($j = $breaks[$i]; $j < $breaks[$i+1]; $j += $slot) {
                                            if ($j >= strtotime($now)) {
                                                $intervals[] = date("h:iA", $j);
                                            }
                                        }
                                    }
                                }
                            } elseif ($now > $timing->to) {
                                continue;
                            } else {
                                // If break is at first index loop starts at open time
                                if ($i == 0) {
                                    for($j = $open_time; $j < $breaks[$i]; $j += $slot) {
                                        $intervals[] = date("h:iA", $j);
                                    }
                                } else {
                                    // If last index then loop ends at closing time
                                    if ($i == count($timing->break)) {
                                        for($j = $breaks[count($breaks) - 1]; $j < $close_time; $j += $slot) {
                                            $intervals[] = date("h:iA", $j);
                                        }
                                    } else {
                                        for($j = $breaks[$i]; $j < $breaks[$i+1]; $j += $slot) {
                                            $intervals[] = date("h:iA", $j);
                                        }
                                    }
                                }
                            }
                        } else {
                            // If break is at first index loop starts at open time
                            if ($i == 0) {
                                for($j = $open_time; $j < $breaks[$i]; $j += $slot) {
                                    $intervals[] = date("h:iA", $j);
                                }
                            } else {
                                // If last index then loop ends at closing time
                                if ($i == count($timing->break)) {
                                    for($j = $breaks[count($breaks) - 1]; $j < $close_time; $j += $slot) {
                                        $intervals[] = date("h:iA", $j);
                                    }
                                } else {
                                    for($j = $breaks[$i]; $j < $breaks[$i+1]; $j += $slot) {
                                        $intervals[] = date("h:iA", $j);
                                    }
                                }
                            }
                        }
                    }
                } else {
                    // Check if date is today
                    if (date('Y-m-d') == date('Y-m-d', strtotime($date))) {
                        $today = new \DateTime("now", new \DateTimeZone($request->timeZone) );
                        $now = $today->format("H:i:s");

                        if ($now > $timing->from && $now < $timing->to) {
                            for($i = $open_time; $i < $close_time; $i += $slot) {
                                if ($i >= strtotime($now)) {
                                    $intervals[] = date("h:iA", $i);
                                }
                            }
                        } elseif ($now > $timing->to) {
                            continue;
                        } else {
                            for($i = $open_time; $i < $close_time; $i += $slot) {
                                $intervals[] = date("h:iA", $i);
                            }
                        }
                    } else {
                        for($i = $open_time; $i < $close_time; $i += $slot) {
                            $intervals[] = date("h:iA", $i);
                        }
                    }
                }

                // Check if doctor is already appointed on that date & time
                if (count($doctor->doctorAppointments)) {
                    foreach ($doctor->doctorAppointments as $appointment) {
                        if ($appointment->appointment_date == $date) {
                            $key = array_search(date("h:iA", strtotime($appointment->from)), $intervals);

                            if ($key !== false) {
                                unset($intervals[$key]);
                                $intervals = array_values($intervals);
                            }
                        }
                    }
                }

                // Separate into morning, afternoon, evening
                $morning = $afternoon = $evening = [];

                for ($i = 0; $i < count($intervals); $i++) {
                    if (strtotime($intervals[$i]) > strtotime('06:00AM') && strtotime($intervals[$i]) < strtotime('12:00PM')) {
                        $morning[] = $intervals[$i];
                    } else if (strtotime($intervals[$i]) >= strtotime('12:00PM') && strtotime($intervals[$i]) < strtotime('06:00PM')) {
                        $afternoon[] = $intervals[$i];
                    } else {
                        $evening[] = $intervals[$i];
                    }
                }

                $timings[date('Y-m-d', strtotime($date))] = ['morning' => $morning, 'afternoon' => $afternoon, 'evening' => $evening];
            } else {
                // Get One-off Availability
                $availabilities = Availability::where(['user_id' => $request->doctor_id, 'type' => 1])->whereDate('from', date('Y-m-d', strtotime($date)))->first();

                if ($availabilities) {
                    // Check if date is today
                    if (date('Y-m-d') == date('Y-m-d', strtotime($date))) {
                        $today = new \DateTime("now", new \DateTimeZone($request->timeZone) );
                        $now = $today->format("H:i:s");

                        if ($now > $availabilities->from_time && $now < $availabilities->to_time) {
                            for ($i = strtotime($availabilities->from_time); $i < strtotime($availabilities->to_time); $i += $slot) {
                                if ($i >= strtotime($now)) {
                                    $intervals[] = date("h:iA", $i);
                                }
                            }
                        } elseif ($now > $availabilities->to_time) {
                            continue;
                        } else {
                            for ($i = strtotime($availabilities->from_time); $i < strtotime($availabilities->to_time); $i += $slot) {
                                $intervals[] = date("h:iA", $i);
                            }
                        }
                    } else {
                        for ($i = strtotime($availabilities->from_time); $i < strtotime($availabilities->to_time); $i += $slot) {
                            $intervals[] = date("h:iA", $i);
                        }
                    }

                    // Check if doctor is already appointed on that date & time
                    if (count($doctor->doctorAppointments)) {
                        foreach ($doctor->doctorAppointments as $appointment) {
                            if ($appointment->appointment_date == $date) {
                                $key = array_search(date("h:iA", strtotime($appointment->from)), $intervals);

                                if ($key !== false) {
                                    unset($intervals[$key]);
                                    $intervals = array_values($intervals);
                                }
                            }
                        }
                    }

                    // Separate into morning, afternoon, evening
                    $morning = $afternoon = $evening = [];

                    for ($i = 0; $i < count($intervals); $i++) {
                        if (strtotime($intervals[$i]) > strtotime('06:00AM') && strtotime($intervals[$i]) < strtotime('12:00PM')) {
                            $morning[] = $intervals[$i];
                        } else if (strtotime($intervals[$i]) >= strtotime('12:00PM') && strtotime($intervals[$i]) < strtotime('06:00PM')) {
                            $afternoon[] = $intervals[$i];
                        } else {
                            $evening[] = $intervals[$i];
                        }
                    }

                    $timings[date('Y-m-d', strtotime($date))] = ['morning' => $morning, 'afternoon' => $afternoon, 'evening' => $evening];
                }
            }
        }

        return response()->json($timings);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Timing  $timing
     * @return \Illuminate\Http\Response
     */
    public function edit(Timing $timing)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Timing  $timing
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Timing $timing)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DayBreak  $day_break
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(DayBreak $day_break)
    {
        DayBreak::destroy($day_break->id);

        return response()->json();
    }
}
