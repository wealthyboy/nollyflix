@unless(request()->is('admin') || request()->is('admin/*'))
    <a
        class="nollyflix-whatsapp-chat"
        href="https://wa.me/2348033821593"
        target="_blank"
        rel="noopener noreferrer"
        aria-label="Chat with Nollyflix on WhatsApp"
        title="Chat with us on WhatsApp"
    >
        <i class="fab fa-whatsapp" aria-hidden="true"></i>
        <span class="sr-only">Chat with Nollyflix on WhatsApp</span>
    </a>
@endunless
