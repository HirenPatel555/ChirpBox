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
                    iconSvg.classList.add('fill-primary', 'text-primary');
                    iconSvg.classList.remove('fill-none', 'text-current');
                } else {
                    iconSvg.classList.add('fill-none', 'text-current');
                    iconSvg.classList.remove('fill-primary', 'text-primary');
                }
            }
        })
        .catch(error => {
            console.error('Error toggling like:', error);
        });
};

function createChirpHtml(chirp) {
    const isOwner = window.userId === chirp.user.id;
    
    // Follow button logic
    let followHtml = '';
    if (window.userId && !isOwner) {
        followHtml = `
            <span class="text-base-content/60">·</span>
            <form method="POST" action="/users/${chirp.user.id}/follow" class="inline">
                <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''}">
                <button type="submit" class="text-xs font-semibold link link-hover text-primary">
                    Follow
                </button>
            </form>
        `;
    }

    return `
        <div class="card bg-base-100 shadow new-chirps-highlight border border-yellow-200" id="chirp-${chirp.id}">
            <div class="card-body">
                <div class="flex space-x-3">
                    <a href="/users/${chirp.user.id}" class="avatar hover:opacity-85 transition-opacity">
                        <div class="size-10 rounded-full">
                            <img src="${chirp.user.avatar_url}" alt="${chirp.user.name}'s avatar" class="rounded-full" />
                        </div>
                    </a>

                    <div class="min-w-0 flex-1">
                        <div class="flex justify-between w-full">
                            <div class="flex items-center gap-1">
                                <a href="/users/${chirp.user.id}" class="text-sm font-semibold link link-hover text-base-content">
                                    ${chirp.user.name}
                                </a>
                                ${followHtml}
                                <span class="text-base-content/60">·</span>
                                <span class="text-sm text-base-content/60">${chirp.created_at}</span>
                            </div>
                        </div>
                        <p class="mt-1 text-base-content">${chirp.message}</p>
                        
                        <div class="mt-3 flex items-center space-x-6">
                            <div class="flex items-center space-x-1" id="like-container-${chirp.id}">
                                ${window.userId ? `
                                    <button 
                                        type="button"
                                        onclick="toggleLike(${chirp.id})"
                                        class="btn btn-ghost btn-xs text-base-content/70 hover:text-primary flex items-center gap-1 focus:outline-none"
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
                                        <span id="like-count-${chirp.id}" class="text-xs font-medium">0</span>
                                    </button>
                                ` : `
                                    <a href="/login" class="btn btn-ghost btn-xs text-base-content/70 hover:text-primary flex items-center gap-1">
                                        <svg 
                                            xmlns="http://www.w3.org/2000/svg" 
                                            class="size-4 fill-none text-current" 
                                            viewBox="0 0 24 24" 
                                            stroke="currentColor"
                                            stroke-width="2"
                                        >
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                                        </svg>
                                        <span class="text-xs font-medium">0</span>
                                    </a>
                                `}
                            </div>

                            <a href="/chirps/${chirp.id}" class="btn btn-ghost btn-xs text-base-content/70 hover:text-primary flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 fill-none text-current" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379L12 21l3.12-3.134c1.152-.086 2.294-.213 3.423-.379 1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                                </svg>
                                <span class="text-xs font-medium">Replies (0)</span>
                            </a>
                        </div>
                    </div>
                </div>
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
                        feedContainer.insertBefore(div.firstElementChild, feedContainer.firstChild);
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
