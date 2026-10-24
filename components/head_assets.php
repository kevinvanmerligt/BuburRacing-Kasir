<?php ?>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Squada+One&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
    .font-logo { font-family: 'Squada One', sans-serif; }
    .font-body { font-family: 'Inter', sans-serif; }
    html { scroll-behavior: smooth; }
    .custom-scroll::-webkit-scrollbar { width: 6px; }
    .custom-scroll::-webkit-scrollbar-track { background: transparent; }
    .custom-scroll::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 10px; }
    @keyframes popIn {
        0% { transform: scale(0.9); opacity: 0; }
        100% { transform: scale(1); opacity: 1; }
    }
    .animate-pop { animation: popIn 0.3s ease-out forwards; }
</style>
