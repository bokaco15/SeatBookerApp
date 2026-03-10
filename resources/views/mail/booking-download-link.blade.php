<h2>Your tickets are ready 🎉</h2>

<p>Hello {{ $booking->email }}</p>

<p>You can download your tickets here:</p>

<a href="{{ route('tickets.download.pdf', $booking->id) }}"
   style="padding:12px 20px;background:black;color:white;text-decoration:none;border-radius:6px;">
    Download tickets
</a>

<p>If the button doesn't work copy this link:</p>

<p>{{ route('tickets.download.pdf', $booking->id) }}</p>
