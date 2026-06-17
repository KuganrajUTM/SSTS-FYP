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

    /* TO (light): */
    .add-container {
        background: var(--bg); /* #f5f7fa - light bg */
        min-height: 100vh;
        padding: 2.5rem 0 6rem;
        display: flex;
        align-items: flex-start;
        justify-content: center;
        position: relative;
    }

    .add-title {
        font-family: 'Syne', sans-serif;
        font-size: clamp(1.8rem, 3vw, 2.5rem);
        font-weight: 800;
        color: var(--navy); 
        letter-spacing: -0.02em;
        margin-bottom: 0.75rem;
    }

    .add-subtitle {
        color: var(--slate);
        font-size: 1.05rem;
        font-weight: 400;
    }

    .add-card {
        background: var(--card-bg);
        border-radius: 20px;
        border: 1.5px solid var(--border);
        box-shadow: 0 20px 60px rgba(0,184,148,0.15);
        overflow: hidden;
        max-width: 600px;
        margin: 0 auto;
    }

    .add-card-inner {
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
        .add-container {
            padding: 1.5rem 0;
        }
        
        .add-card-inner {
            padding: 1.75rem 1.5rem;
        }
        
        .add-title {
            font-size: 1.75rem;
        }
        
        .add-subtitle {
            font-size: 0.95rem;
        }
        
        .form-input, .form-textarea {
            padding: 0.85rem 1rem;
            font-size: 0.92rem;
        }
    }
</style>

<div class="add-container">
    <div class="container mx-auto px-4">
        <div class="text-center mb-8">
            <h2 class="add-title">Add New Announcement</h2>
            <p class="add-subtitle">Share important updates with the EduTransit community.</p>
        </div>

        <div class="add-card">
            <div class="add-card-inner">
                <form action="{{ route('addann') }}" method="POST">
                    @csrf
                    
                    <div class="mb-6">
                        <label for="title" class="form-label">
                            Title <span class="form-required">*</span>
                        </label>
                        <input type="text" 
                               name="title" 
                               id="title" 
                               class="form-input" 
                               placeholder="Enter title" 
                               required>
                    </div>

                    <div class="mb-6">
                        <label for="content" class="form-label">
                            Content <span class="form-required">*</span>
                        </label>
                        <textarea name="content" 
                                  id="content" 
                                  rows="6" 
                                  class="form-textarea" 
                                  placeholder="Enter content" 
                                  required></textarea>
                    </div>

                    <div class="flex flex-col space-y-4">
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-paper-plane"></i> Send Announcement
                        </button>
                        
                        <div class="form-note">
                            <span class="required">*</span> indicates a required field
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection