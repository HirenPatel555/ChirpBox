/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
  ],
  theme: {
    extend: {
      colors: {
        bgBase: '#0F0B1E',
        bgCard: '#1A1530',
        bgCardHover: '#221C3D',
        accentCoral: '#FF5D8F',
        accentViolet: '#7C3AED',
        accentGold: '#FFC857',
        textPrimary: '#F5F3FF',
        textMuted: '#A8A3C0',
        borderSubtle: '#2C2547',
      },
      fontFamily: {
        display: ['Space Grotesk', 'sans-serif'],
        body: ['Inter', 'sans-serif'],
        mono: ['JetBrains Mono', 'monospace'],
      },
    },
  },
  plugins: [],
}
