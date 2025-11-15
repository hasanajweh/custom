<?php
// app/Notifications/NewFileUploaded.php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\FileSubmission;

class NewFileUploaded extends Notification implements ShouldQueue
{
    use Queueable;

    protected $fileSubmission;

    public function __construct(FileSubmission $fileSubmission)
    {
        $this->fileSubmission = $fileSubmission->load(['user', 'subject', 'grade']);
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toArray($notifiable)
    {
        $type = $this->fileSubmission->submission_type;
        $planTypes = ['daily_plan', 'weekly_plan', 'monthly_plan'];

        // Build the message based on file type
        if (in_array($type, $planTypes)) {
            $planName = str_replace('_', ' ', $type);
            $planName = ucwords($planName);
            $message = $this->fileSubmission->user->name . ' uploaded a ' . $planName;
        } else {
            $message = $this->fileSubmission->user->name . ' uploaded ' . $this->fileSubmission->title;
            if ($this->fileSubmission->subject && $this->fileSubmission->grade) {
                $message .= ' for ' . $this->fileSubmission->subject->name . ' - ' . $this->fileSubmission->grade->name;
            }
        }

        return [
            'title' => 'New File Uploaded',
            'message' => $message,
            'file_id' => $this->fileSubmission->id,
            'type' => $type,
            'uploaded_by' => $this->fileSubmission->user->name,
            'file_title' => $this->fileSubmission->title,
            'subject' => $this->fileSubmission->subject ? $this->fileSubmission->subject->name : null,
            'grade' => $this->fileSubmission->grade ? $this->fileSubmission->grade->name : null,
            'uploaded_at' => $this->fileSubmission->created_at->format('M d, Y h:i A'),
        ];
    }

    public function toBroadcast($notifiable)
    {
        return [
            'data' => $this->toArray($notifiable),
            'sound' => true
        ];
    }
}
