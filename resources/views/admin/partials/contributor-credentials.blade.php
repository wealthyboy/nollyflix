<div class="card" style="margin:20px 0; clear:both;">
    <div class="card-content">
        <h4 class="card-title">Contributor login credentials</h4>
        <p><strong>Login email:</strong> <span id="contributor-login-email">{{ $contributor->email }}</span>
            <button type="button" class="btn btn-xs btn-default" onclick="nfxCopyCredential('contributor-login-email')">Copy email</button>
        </p>
        <p><strong>Temporary password:</strong>
            <code id="contributor-password" style="letter-spacing:1px">{{ $contributor->temporary_password_ciphertext ? 'xxxxxx'.substr(app('App\Services\ContributorCredentials')->availablePassword($contributor), -2) : 'Not available' }}</code>
            <button type="button" class="btn btn-xs btn-primary" id="nfx-reveal-password" {{ $contributor->temporary_password_ciphertext ? '' : 'disabled' }}>Reveal</button>
            <button type="button" class="btn btn-xs btn-default" id="nfx-copy-password" disabled>Copy</button>
        </p>
        <p class="text-muted">Only administrators can reveal the encrypted temporary password. It disappears after the contributor signs in. Do not share it by email. Copy it and send through a private channel.</p>
        <form action="{{ $resetRoute }}" method="POST" onsubmit="return confirm('Generate a new temporary password? The old password will stop working immediately.');">
            @csrf
            <button type="submit" class="btn btn-warning btn-sm">Generate / reset temporary password</button>
        </form>
    </div>
</div>
<script>
(function(){
    var revealed = null;
    var show = document.getElementById('nfx-reveal-password');
    var copy = document.getElementById('nfx-copy-password');
    var output = document.getElementById('contributor-password');
    if (!show) return;
    function copyText(value) {
        if (navigator.clipboard && window.isSecureContext) return navigator.clipboard.writeText(value);
        var text = document.createElement('textarea'); text.value=value; document.body.appendChild(text); text.select(); document.execCommand('copy'); text.remove(); return Promise.resolve();
    }
    window.nfxCopyCredential = function(id) { copyText(document.getElementById(id).textContent.trim()); };
    show.addEventListener('click', function(){
        if (revealed !== null) { revealed = null; output.textContent = @json($contributor->temporary_password_ciphertext ? 'xxxxxx'.substr(app('App\Services\ContributorCredentials')->availablePassword($contributor), -2) : 'Not available'); show.textContent='Reveal'; copy.disabled=true; return; }
        fetch(@json($revealRoute), {method:'POST', credentials:'same-origin', headers:{'X-CSRF-TOKEN':@json(csrf_token()), 'Accept':'application/json'}})
            .then(function(r){ if (!r.ok) throw Error('Unable to reveal password'); return r.json(); })
            .then(function(data){ if (!data.available) {output.textContent='No temporary password available';return;} revealed=data.password; output.textContent=data.password; show.textContent='Hide'; copy.disabled=false; })
            .catch(function(){alert('Unable to reveal password. Please refresh and try again.');});
    });
    copy.addEventListener('click', function(){ if (revealed !== null) copyText(revealed); });
})();
</script>
