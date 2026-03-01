{{-- Shared Confirm Modal --}}
<dialog id="confirmModal" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg" id="confirmTitle">Konfirmasi</h3>
        <p class="py-4" id="confirmMessage">Apakah Anda yakin?</p>
        <div class="modal-action">
            <button class="btn btn-ghost btn-sm" onclick="confirmModal.close()">Batal</button>
            <button class="btn btn-primary btn-sm" id="confirmBtn" onclick="confirmAction()">Ya, Lanjutkan</button>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop"><button>close</button></form>
</dialog>

<script>
    let pendingForm = null;

    function showConfirm(form, message, title) {
        pendingForm = form;
        document.getElementById('confirmMessage').textContent = message;
        if (title) document.getElementById('confirmTitle').textContent = title;
        confirmModal.showModal();
    }

    function confirmAction() {
        if (pendingForm) {
            pendingForm.submit();
            pendingForm = null;
        }
        confirmModal.close();
    }
</script>
