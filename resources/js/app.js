import './bootstrap';

window.toggleLike = function (chirpId) {
    axios.post(`/chirps/${chirpId}/like`)
        .then(response => {
            const countSpan = document.getElementById(`like-count-${chirpId}`);
            const iconSvg = document.getElementById(`like-icon-${chirpId}`);
            
            if (countSpan) {
                countSpan.textContent = response.data.likes_count;
            }
            if (iconSvg) {
                if (response.data.liked) {
                    iconSvg.classList.add('fill-accentCoral', 'text-accentCoral');
                    iconSvg.classList.remove('fill-none', 'text-current');
                } else {
                    iconSvg.classList.add('fill-none', 'text-current');
                    iconSvg.classList.remove('fill-accentCoral', 'text-accentCoral');
                }
            }
        })
        .catch(error => {
            console.error('Error toggling like:', error);
        });
};

function createChirpHtml(chirp) {
    const isOwner = window.userId === chirp.user.id;
    const cleanHandle = chirp.user.name.toLowerCase().replace(/\s+/g, '');
    
    // Follow button logic
    let followHtml = '';
    if (window.userId && !isOwner) {
        followHtml = `
            <span class="text-textMuted font-mono text-xs">·</span>
            <form method="POST" action="/users/${chirp.user.id}/follow" class="inline">
                <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''}">
                <button type="submit" class="font-body font-semibold text-xs text-accentViolet hover:text-opacity-80">
                    Follow
                </button>
            </form>
        `;
    }

    return `
        <div class="bg-bgCard rounded-2xl border border-borderSubtle p-5 hover:bg-bgCardHover transition-all duration-300 space-y-3 new-chirps-highlight" id="chirp-card-${chirp.id}">
            <div class="flex items-start justify-between gap-4">
                <div class="flex items-center gap-3">
                    <a href="/users/${chirp.user.id}" class="avatar hover:opacity-90 transition-opacity shrink-0">
                        <img src="${chirp.user.avatar_url}" alt="${chirp.user.name}'s avatar" class="size-8 rounded-full border border-borderSubtle/50 object-cover" />
                    </a>
                    
                    <div class="flex flex-col sm:flex-row sm:items-center sm:gap-2">
                        <a href="/users/${chirp.user.id}" class="font-body font-semibold text-textPrimary hover:underline text-sm leading-none">
                            ${chirp.user.name}
                        </a>
                        <span class="font-mono text-textMuted text-xs">
                            @${cleanHandle}
                        </span>
                    </div>
                    ${followHtml}
                    <span class="text-textMuted font-mono text-xs">·</span>
                    <span class="font-mono text-textMuted text-xs">${chirp.created_at}</span>
                </div>
                
                <!-- Soundwave Pulse Container -->
                <div class="soundwave-container transition-opacity duration-500 opacity-100 flex items-center justify-center shrink-0">
                    <div class="soundwave-pulse flex items-end gap-[3px] h-4 w-6" aria-hidden="true">
                        <span class="soundwave-bar w-[3px] h-full bg-accentGold origin-bottom rounded-full"></span>
                        <span class="soundwave-bar w-[3px] h-full bg-accentGold origin-bottom rounded-full"></span>
                        <span class="soundwave-bar w-[3px] h-full bg-accentGold origin-bottom rounded-full"></span>
                        <span class="soundwave-bar w-[3px] h-full bg-accentGold origin-bottom rounded-full"></span>
                    </div>
                </div>
            </div>
            <p class="font-body text-textPrimary text-base leading-relaxed break-words whitespace-pre-wrap">${chirp.message}</p>
            <div class="flex items-center gap-6 pt-2 border-t border-borderSubtle/50 text-textMuted font-mono">
                <div class="flex items-center space-x-1" id="like-container-${chirp.id}">
                    ${window.userId ? `
                        <button 
                            type="button"
                            onclick="toggleLike(${chirp.id})"
                            class="btn btn-link btn-xs no-underline text-textMuted hover:text-accentCoral flex items-center gap-1.5 focus:outline-none p-0"
                            id="like-button-${chirp.id}"
                        >
                            <svg 
                                xmlns="http://www.w3.org/2000/svg" 
                                class="size-4 transition-all duration-300 fill-none text-current" 
                                viewBox="0 0 24 24" 
                                stroke="currentColor"
                                stroke-width="2"
                                id="like-icon-${chirp.id}"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                            </svg>
                            <span id="like-count-${chirp.id}" class="text-xs font-semibold">0</span>
                        </button>
                    ` : `
                        <a href="/login" class="btn btn-link btn-xs no-underline text-textMuted hover:text-accentCoral flex items-center gap-1.5 p-0">
                            <svg 
                                xmlns="http://www.w3.org/2000/svg" 
                                class="size-4 fill-none text-current" 
                                viewBox="0 0 24 24" 
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                            </svg>
                            <span class="text-xs font-semibold">0</span>
                        </a>
                    `}
                </div>
                ${!chirp.parent_id ? `
                    <a href="/chirps/${chirp.id}" class="hover:text-textPrimary transition-colors flex items-center gap-1.5 text-xs">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4 fill-none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379L12 21l3.12-3.134c1.152-.086 2.294-.213 3.423-.379 1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                        </svg>
                        <span>0</span>
                    </a>
                ` : ''}
            </div>
        </div>
    `;
}

document.addEventListener('DOMContentLoaded', () => {
    if (typeof window.Echo !== 'undefined') {
        // Listen for new chirps (Step 3.2)
        window.Echo.channel('chirps')
            .listen('ChirpCreated', (e) => {
                // Only prepend top-level chirps and ensure it's not the current user's (avoiding duplicate on reload)
                if (e.parent_id === null && e.user.id !== window.userId) {
                    const feedContainer = document.getElementById('chirps-feed');
                    if (feedContainer) {
                        // Remove empty state if present
                        const emptyState = feedContainer.querySelector('.hero');
                        if (emptyState) {
                            emptyState.remove();
                        }
                        
                        // Parse template and prepend to feed
                        const div = document.createElement('div');
                        div.innerHTML = createChirpHtml(e);
                        const newCard = div.firstElementChild;
                        feedContainer.insertBefore(newCard, feedContainer.firstChild);

                        // Fade out soundwave pulse icon after 2 seconds
                        setTimeout(() => {
                            const soundwave = newCard.querySelector('.soundwave-container');
                            if (soundwave) {
                                soundwave.classList.add('opacity-0');
                                soundwave.classList.remove('opacity-100');
                                setTimeout(() => soundwave.remove(), 500); // Remove from DOM after fade completes
                            }
                        }, 2000);
                    }
                }
            })
            // Listen for like toggle updates (Step 3.3)
            .listen('ChirpLiked', (e) => {
                const countSpan = document.getElementById(`like-count-${e.chirp_id}`);
                if (countSpan) {
                    countSpan.textContent = e.likes_count;
                }
            });

        // Join presence indicator channel (Step 3.4)
        if (window.userId) {
            const presenceIndicator = document.getElementById('presence-indicator');
            const presenceCount = document.getElementById('presence-count');
            
            if (presenceIndicator && presenceCount) {
                presenceIndicator.classList.remove('hidden');
                presenceIndicator.classList.add('flex');
                
                window.Echo.join('online')
                    .here((users) => {
                        presenceCount.textContent = users.length;
                    })
                    .joining((user) => {
                        const current = parseInt(presenceCount.textContent) || 0;
                        presenceCount.textContent = current + 1;
                    })
                    .leaving((user) => {
                        const current = parseInt(presenceCount.textContent) || 0;
                        presenceCount.textContent = Math.max(0, current - 1);
                    });
            }
        }
    }
});
