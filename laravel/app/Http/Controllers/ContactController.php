<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;


class ContactController extends Controller
{
    public function sendMail(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'message' => 'required|string',
        ]);
        $data = [
            'subject' => $validated['subject'],
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'] ?? '', 
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? '',
            'message_content' => $validated['message'],
        ];


        Mail::send('emails.contact', $data, function($message) use ($data) {
            $message->to('qqla83@gmail.com')
                    ->subject($data['subject']); 
        });

        
        return view('redirection_info')
            ->with('id', 'send_form_confirmation')
            ->with('title', __( 'base.contact_confirm_label' ) )
            ->with('infoAnnouncement', __('base.contact_confirm_info') )
            ->with('redirectionRouteName', 'main')
            ->with('redirectedText', __('base.contact_redirected_text') )
            ->with('delayTime', 5000 );
    }
}