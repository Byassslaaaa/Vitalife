<?php

namespace App\Exports;

use App\Models\SpaBooking;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SpaAnalyticsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $startDate;
    protected $endDate;

    public function __construct(Carbon $startDate, Carbon $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return SpaBooking::whereBetween('created_at', [$this->startDate, $this->endDate])
            ->with('spa')
            ->orderByDesc('created_at')
            ->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Booking Code',
            'Customer Name',
            'Customer Email',
            'Customer Phone',
            'Spa Location',
            'Service Name',
            'Service Price',
            'Booking Date',
            'Booking Time',
            'Status',
            'Payment Status',
            'Created At',
        ];
    }

    /**
     * @param mixed $booking
     * @return array
     */
    public function map($booking): array
    {
        return [
            $booking->booking_code,
            $booking->customer_name,
            $booking->customer_email,
            $booking->customer_phone,
            $booking->spa ? $booking->spa->nama : 'N/A',
            $booking->service_name,
            $booking->service_price,
            $booking->booking_date ? Carbon::parse($booking->booking_date)->format('Y-m-d') : 'N/A',
            $booking->booking_time ? Carbon::parse($booking->booking_time)->format('H:i') : 'N/A',
            ucfirst($booking->status),
            ucfirst($booking->payment_status),
            $booking->created_at->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Spa Analytics';
    }
}
