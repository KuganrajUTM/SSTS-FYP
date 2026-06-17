@extends('layout.main-template')

@section('content')
<style>
    :root {
      --emerald:    #00b894;
      --emerald-dk: #007a63;
      --emerald-lt: #e6f9f5;
      --navy:       #0a1628;      
      --navy-mid:   #132035;      
      --slate:      #4a5568;      
      --white:      #ffffff;
      --bg:         #f5f7fa;     
      --card-bg:    #ffffff;    
      --input-bg:   #f0f4f8;      
      --border:     rgba(0,184,148,0.25); 
    }

    .edit-container {
        background: var(--bg);
        min-height: 100vh;
        padding: 2.5rem 0 6rem;
        display: flex;
        align-items: flex-start;
        justify-content: center;
        position: relative;
    }

    .edit-title {
        font-family: 'Syne', sans-serif;
        font-size: clamp(1.8rem, 3vw, 2.5rem);
        font-weight: 800;
        color: var(--navy); 
        letter-spacing: -0.02em;
        margin-bottom: 0.75rem;
    }

    .edit-subtitle {
        color: var(--slate);
        font-size: 1.05rem;
        font-weight: 400;
    }

    .edit-card {
        background: var(--card-bg);
        border-radius: 20px;
        border: 1.5px solid var(--border);
        box-shadow: 0 20px 60px rgba(0,184,148,0.15);
        overflow: hidden;
        max-width: 600px;
        margin: 0 auto;
    }

    .edit-card-inner {
        padding: 2.5rem 2.5rem;
    }

    .form-label {
        font-family: 'Syne', sans-serif;
        font-size: 0.92rem;
        font-weight: 700;
        color: var(--navy);
        margin-bottom: 0.75rem;
        display: block;
    }

    .form-required {
        color: #ef4444;
    }

    .form-input, .form-textarea {
        width: 100%;
        background: var(--input-bg);
        border: 1.5px solid rgba(74,85,104,0.3);
        color: var(--navy);
        font-size: 0.95rem;
        padding: 0.9rem 1.1rem;
        border-radius: 12px;
        font-family: 'DM Sans', sans-serif;
        transition: all 0.25s ease;
        font-weight: 400;
    }

    .form-input::placeholder, .form-textarea::placeholder {
        color: var(--slate);
        opacity: 0.7;
    }

    .form-input:focus, .form-textarea:focus {
        outline: none;
        border-color: var(--emerald);
        background: var(--white);
        box-shadow: 0 0 0 4px rgba(0,184,148,0.15);
        transform: translateY(-1px);
    }

    .form-textarea {
        resize: vertical;
        min-height: 140px;
        font-family: 'DM Sans', sans-serif;
    }

    .btn-submit {
        background: linear-gradient(135deg, var(--emerald) 0%, var(--emerald-dk) 100%);
        color: var(--white) !important;
        font-family: 'Syne', sans-serif;
        font-weight: 700;
        font-size: 0.95rem;
        padding: 1rem 2rem;
        border-radius: 12px;
        border: none;
        width: 100%;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 8px 25px rgba(0,184,148,0.35);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        letter-spacing: 0.02em;
        text-transform: none;
    }

    .btn-submit:hover {
        background: linear-gradient(135deg, var(--emerald-dk) 0%, #00654d 100%);
        transform: translateY(-2px);
        box-shadow: 0 12px 35px rgba(0,184,148,0.45);
        color: var(--white) !important;
    }

    .form-note {
        font-size: 0.8rem;
        color: var(--slate);
        font-style: italic;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .form-note .required {
        color: #ef4444;
    }

    @media (max-width: 576px) {
        .edit-container {
            padding: 1.5rem 0;
        }
        
        .edit-card-inner {
            padding: 1.75rem 1.5rem;
        }
        
        .edit-title {
            font-size: 1.75rem;
        }
        
        .edit-subtitle {
            font-size: 0.95rem;
        }
        
        .form-input, .form-textarea {
            padding: 0.85rem 1rem;
            font-size: 0.92rem;
        }
    }

    .overlay-notification {
        position: fixed;
        top: 20px;
        left: 50%;
        transform: translateX(-50%);
        background: linear-gradient(135deg, var(--emerald), var(--emerald-dk));
        color: var(--white);
        padding: 15px 20px;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0,184,148,0.4);
        z-index: 1050;
        display: none;
        font-size: 1rem;
        font-weight: 500;
        font-family: 'Syne', sans-serif;
        animation: fadeInOut 5s forwards;
        border: 1px solid rgba(255,255,255,0.2);
    }

    @keyframes fadeInOut {
        0% { opacity: 0; transform: translateX(-50%) translateY(-20px); }
        10% { opacity: 1; transform: translateX(-50%) translateY(0); }
        90% { opacity: 1; transform: translateX(-50%) translateY(0); }
        100% { opacity: 0; transform: translateX(-50%) translateY(-20px); }
    }
</style>

<div class="edit-container">
    @if (session('success') || session('error'))
        <div class="overlay-notification" id="successNotification">
            {{ session('success') ?? session('error') }}
        </div>
    @endif

    <div class="edit-card">
        <div class="edit-card-inner">
            <h1 class="edit-title mb-2">Edit Announcement</h1>
            <p class="edit-subtitle mb-4">Update your announcement details below</p>
            
            <form action="{{ route('ann.update', $announcement->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-4">
                    <label for="title" class="form-label">
                        Title <span class="form-required">*</span>
                    </label>
                    <input type="text" 
                           name="title" 
                           id="title" 
                           class="form-input" 
                           value="{{ $announcement->title }}" 
                           required
                           placeholder="Enter announcement title">
                </div>
                
                <div class="mb-4">
                    <label for="content" class="form-label">
                        Content <span class="form-required">*</span>
                    </label>
                    <textarea name="content" 
                              id="content" 
                              class="form-textarea" 
                              required
                              placeholder="Enter announcement content...">{{ $announcement->content }}</textarea>
                </div>
                
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i>
                    Save Changes
                </button>
                
                <div class="form-note mt-3">
                    <span class="required form-required">*</span>
                    required field
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const notification = document.getElementById('successNotification');
    if (notification) {
        notification.style.display = 'block';
        setTimeout(() => {
            notification.style.display = 'none';
        }, 5000);
    }
});
</script>

@endsection