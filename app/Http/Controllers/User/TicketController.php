<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::where('user_id', Auth::id())->latest();

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $tickets = $query->paginate(10);
        return view('user.tickets.index', compact('tickets'));
    }

    public function show($id)
    {
        $ticket = Ticket::where('id', $id)
            ->where('user_id', Auth::id())
            ->with(['replies.user'])
            ->firstOrFail();

        return view('user.tickets.show', compact('ticket'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'category' => 'required|string',
            'priority' => 'required|in:low,medium,high',
            'message' => 'required|string',
            'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120'
        ]);

        try {
            DB::transaction(function () use ($request) {
                // Ticket ID is generated automatically by Model Boot method
                $ticket = Ticket::create([
                    'user_id' => Auth::id(),
                    'subject' => $request->subject,
                    'category' => $request->category,
                    'priority' => $request->priority,
                    'status' => 'open'
                ]);

                $attachmentPaths = [];
                if ($request->hasFile('attachments')) {
                    foreach ($request->file('attachments') as $file) {
                        $attachmentPaths[] = $file->store('support-tickets', 'public');
                    }
                }

                TicketReply::create([
                    'ticket_id' => $ticket->id,
                    'user_id' => Auth::id(),
                    'message' => $request->message,
                    'attachments' => !empty($attachmentPaths) ? $attachmentPaths : null
                ]);
            });

            return redirect()->route('user.tickets')->with('success', 'Ticket created successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Unable to create ticket. Please try again.');
        }
    }

    public function reply(Request $request, $id)
    {
        $ticket = Ticket::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        if ($ticket->status === 'closed') {
            return back()->with('error', 'This ticket is closed. Please open a new one.');
        }

        $request->validate([
            'message' => 'required|string',
            'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120'
        ]);

        $attachmentPaths = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $attachmentPaths[] = $file->store('support-tickets', 'public');
            }
        }

        TicketReply::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $request->message,
            'attachments' => !empty($attachmentPaths) ? $attachmentPaths : null
        ]);

        // If ticket was previously resolved/replied, set back to open so admin sees it
        if ($ticket->status !== 'open') {
            $ticket->update(['status' => 'open']);
        }

        return back()->with('success', 'Reply posted successfully.');
    }

    public function close($id)
    {
        $ticket = Ticket::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        
        $ticket->update(['status' => 'closed']);
        
        return back()->with('success', 'Ticket marked as closed.');
    }
}