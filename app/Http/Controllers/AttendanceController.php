<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function __construct()
    {
        Carbon::setLocale('ru');
        setlocale(LC_TIME, 'ru_RU.UTF-8');
    }

    public function index()
    {
        $classes = SchoolClass::all()->sortBy(function($class) {
            preg_match('/^\d+/', $class->name, $matches);
            return $matches[0] ?? 0;
        })->values();
        return view('attendance.index', compact('classes'));
    }

    public function storePayment(Request $request)
    {
        Payment::create([
            'student_id' => $request->student_id,
            'payment_date' => $request->payment_date,
            'amount' => $request->amount,
            'type' => $request->type,
            'comment' => $request->comment,
        ]);

        return redirect()->back()->with('payment_success', 'Платеж успешно добавлен!');
    }

    public function deletePayment(SchoolClass $class, Payment $payment)
    {
        $payment->delete();
        return redirect()->back()->with('payment_deleted', 'Платеж удален!');
    }

    public function show(Request $request, SchoolClass $class)
    {
        $year = $request->input('year', now()->year);
        $month = $request->input('month', now()->month);

        $startOfMonth = Carbon::create($year, $month, 1);
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        $days = [];
        $currentDay = $startOfMonth->copy();
        while ($currentDay->lte($endOfMonth)) {
            $days[] = $currentDay->copy();
            $currentDay->addDay();
        }

        $students = $class->students()
            ->orderBy('surname')
            ->with([
                'attendances' => function ($query) use ($startOfMonth, $endOfMonth) {
                    $query->whereBetween('date', [$startOfMonth, $endOfMonth]);
                },
                'payments' => function ($query) use ($startOfMonth, $endOfMonth) {
                    $query->whereBetween('payment_date', [$startOfMonth, $endOfMonth])
                        ->orderBy('payment_date', 'desc');
                }
            ])
            ->get();

        $totalPayments = $students->sum(function($student) {
            return $student->payments->sum('amount');
        });

        return view('attendance.table', compact('class', 'students', 'days', 'year', 'month', 'totalPayments'));
    }

    public function store(Request $request)
    {

        $upsertData = [];

        foreach ($request->attendance as $studentId => $dates) {
            foreach ($dates as $date => $status) {
                if ($status) {
                    $upsertData[] = [
                        'student_id' => $studentId,
                        'date' => $date,
                        'status' => $status,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }

        if (!empty($upsertData)) {
            Attendance::upsert(
                $upsertData,
                ['student_id', 'date'],
                ['status', 'updated_at']
            );
        }

        return redirect()->back()->with('success', 'Табель успешно сохранен!');
    }
}
