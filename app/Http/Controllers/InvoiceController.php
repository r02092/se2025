<?php

namespace App\Http\Controllers;

use App\Traits\ToStringTrait;
use setasign\Fpdi\Tfpdf\Fpdi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class InvoiceController extends Controller
{
    use ToStringTrait;

    public function get()
    {
        define('FPDF_FONTPATH', __DIR__ . '/../../../resources/invoice/');
        $pdf = new Fpdi();
        $pdf->SetAuthor('グループ2 Share', true);
        $pdf->SetTitle('御請求書', true);
        $pdf->SetCreator('SceneTrip', true);
        $pdf->AddFont('BIZUDPMincho', '', 'BIZUDPMincho-Regular.ttf', true);
        $pdf->SetFont('BIZUDPMincho', '', 24);
        $pdf->setSourceFile(FPDF_FONTPATH . 'template.pdf');
        $pdf->SetMargins(27, 53);
        $pdf->AddPage();
        $pdf->useTemplate($pdf->importPage(1));
        $user = Auth::user();
        $pdf->Write(12, $user->name . '　御中');
        $pdf->Ln();
        $pdf->SetFontSize(10.5);
        $pdf->Write(6, $this->postalCodeToString($user->postal_code));
        $pdf->Ln();
        $pdf->Write(
            6,
            $this->cityToString($user->addr_city) . $user->addr_detail,
        );
        $pdf->Ln();
        $pdf->SetXY(27, 53);
        $pdf->Cell(
            156,
            6,
            date('Ymd-') . str_pad($user->id, 3, '0', STR_PAD_LEFT),
            0,
            2,
            'R',
        );
        $pdf->Cell(156, 6, date('Y年n月j日'), 0, 2, 'R');
        $pdf->SetFontSize(16);
        $pdf->SetXY(90, 96);
        $total = ($user->num_plan_std * 3 + $user->num_plan_prm * 5) * 1000;
        $pdf->Write(10, '¥ ' . number_format($total * 1.1));
        $pdf->SetFontSize(10.5);
        $pdf->SetXY(27, 116.4);
        if ($user->num_plan_prm) {
            $pdf->Cell(100, 6.2, 'SceneTrip プレミアムプラン');
            $pdf->Cell(15, 6.2, $user->num_plan_prm, 0, 0, 'R');
            $pdf->Cell(20, 6.2, '¥ 5,000', 0, 0, 'R');
            $pdf->Cell(
                20,
                6.2,
                '¥ ' . number_format($user->num_plan_prm * 5000),
                0,
                2,
                'R',
            );
        }
        if ($user->num_plan_std) {
            $pdf->Cell(100, 6.2, 'SceneTrip スタンダードプラン');
            $pdf->Cell(15, 6.2, $user->num_plan_std, 0, 0, 'R');
            $pdf->Cell(20, 6.2, '¥ 3,000', 0, 0, 'R');
            $pdf->Cell(
                20,
                6.2,
                '¥ ' . number_format($user->num_plan_prm * 3000),
                0,
                2,
                'R',
            );
        }
        $pdf->SetXY(27, 209.4);
        $pdf->Cell(155, 6.2, '¥ ' . number_format($total), 0, 2, 'R');
        $pdf->Cell(155, 6.2, '¥ ' . number_format($total / 10), 0, 2, 'R');
        $pdf->Cell(155, 6.2, '¥ ' . number_format($total * 1.1), 0, 2, 'R');
        $pdf->SetXY(65, 250.6);
        $pdf->Write(6.2, date('Y年n月j日', strtotime('+1 month')));
        return Response::make($pdf->Output(), 200, [
            'Content-Type' => 'application/pdf',
        ]);
    }
}
