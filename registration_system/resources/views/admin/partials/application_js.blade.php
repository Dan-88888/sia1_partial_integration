<style>
    /* Ensure action buttons are always at the front and clickable */
    .admin-actions {
        z-index: 100 !important;
        position: relative !important;
        pointer-events: auto !important;
    }
    .btn-sm-action {
        cursor: pointer !important;
        pointer-events: auto !important;
        z-index: 101 !important;
    }
</style>

<script>
/**
 * Shared Administrative Actions for Admission Management
 */

function approveApp(id, name) {
    console.log(`[AdminAction] Attempting to approve: ${id} (${name})`);
    // Ensure name is clean for display
    const displayName = String(name).replace(/['"]/g, '');
    
    Swal.fire({
        title: 'Approve Admission?',
        text: `This will create a new account for ${displayName} with the password format nameNNNN.`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28c76f',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Yes, Approve Admission'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({ 
                title: 'Processing...', 
                text: 'Please wait while we create the student account...',
                allowOutsideClick: false, 
                didOpen: () => { Swal.showLoading(); } 
            });
            
            fetch("{{ url('/') }}/admin/applications/" + id + "/approve", {
                method: "POST",
                headers: { 
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json",
                    "Content-Type": "application/json"
                }
            })
            .then(async res => {
                const data = await res.json().catch(() => null);
                if (!res.ok) {
                    throw new Error(data?.message || `Server returned ${res.status}`);
                }
                return data;
            })
            .then(data => {
                Swal.fire({
                    title: 'Approved!',
                    text: data.message,
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => location.reload());
            })
            .catch(err => {
                console.error('[AdminAction] Approval Failed:', err);
                Swal.fire('Error', 'Failed to approve. ' + err.message, 'error');
            });
        }
    });
}

function rejectApp(id, name) {
    console.log(`[AdminAction] Attempting to reject: ${id} (${name})`);
    Swal.fire({
        title: 'Reject Application?',
        text: `Are you sure you want to reject ${name}'s application?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ff9f43',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Yes, Reject'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch("{{ url('/') }}/admin/applications/" + id + "/reject", {
                method: "POST",
                headers: { 
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json",
                    "Content-Type": "application/json"
                }
            })
            .then(async res => {
                const data = await res.json().catch(() => null);
                if (!res.ok) throw new Error(data?.message || `Server error ${res.status}`);
                return data;
            })
            .then(data => {
                Swal.fire({
                    title: 'Rejected',
                    text: data.message,
                    icon: 'info',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => location.reload());
            })
            .catch(err => {
                console.error('[AdminAction] Rejection Failed:', err);
                Swal.fire('Error', 'Failed to reject application.', 'error');
            });
        }
    });
}

function deleteApp(id) {
    console.log(`[AdminAction] Attempting to delete record: ${id}`);
    Swal.fire({
        title: 'Delete Record?',
        text: "This will permanently remove this application from the history logs.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ea5455',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Yes, Delete'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch("{{ url('/') }}/admin/applications/" + id, {
                method: "DELETE",
                headers: { 
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json",
                    "Content-Type": "application/json"
                }
            })
            .then(async res => {
                const data = await res.json().catch(() => null);
                if (!res.ok) throw new Error(data?.message || `Server error ${res.status}`);
                return data;
            })
            .then(data => {
                Swal.fire({
                    title: 'Deleted',
                    text: data.message,
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => location.reload());
            })
            .catch(err => {
                console.error('[AdminAction] Deletion Failed:', err);
                Swal.fire('Error', 'Failed to delete record.', 'error');
            });
        }
    });
}
function clearAllApplications() {
    Swal.fire({
        title: 'Clear All Records?',
        text: "This will permanently delete ALL application records. There is no undo!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ea5455',
        confirmButtonText: 'Yes, Clear All'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch("{{ url('/') }}/admin/applications/clear-all", {
                method: "POST",
                headers: { 
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json"
                }
            })
            .then(res => res.json())
            .then(res => {
                Swal.fire('Cleared!', res.message, 'success').then(() => location.reload());
            });
        }
    });
}
</script>
