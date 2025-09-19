<?php

namespace App\Exports;

use App\Models\FormAnswer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AnswerExport implements FromCollection, WithHeadings
{
    protected $formId;
    protected $sections;

    public function __construct($formId)
    {
        $this->formId = $formId;
        $this->sections = \App\Models\Section::with('questions')
            ->where('form_id', $formId)
            ->orderBy('id')
            ->get();
    }

    public function collection()
    {
        $jawaban = FormAnswer::with(['user.pegawai', 'details.question.section'])
            ->where('form_id', $this->formId)
            ->get();

        $data = [];

        foreach ($jawaban as $j) {
            $row = [
                'Nama Pegawai' => $j->user->pegawai->nama ?? '-',
                'Tanggal'   => $j->created_at->format('d-m-Y H:i'),
            ];

            foreach ($this->sections as $section) {
                $row["Section ({$section->title})"] = $section->title;

                foreach ($section->questions as $q) {
                    $detail = $j->details->firstWhere('question_id', $q->id);
                    $row[$q->text] = $detail->answer_text ?? '';
                }
            }

            $data[] = $row;
        }

        return collect($data);
    }

    public function headings(): array
    {
        $headings = ['Nama Pegawai', 'Tanggal'];

        foreach ($this->sections as $section) {
            $headings[] = "Section";

            foreach ($section->questions as $q) {
                $headings[] = $q->text;
            }
        }

        return $headings;
    }
}
