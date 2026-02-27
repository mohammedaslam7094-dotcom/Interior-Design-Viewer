const fs = require('fs');
const path = require('path');

const filesToProcess = [
    'admin/dashboard.php',
    'admin/materials.php',
    'admin/feedback.php',
    'pages/materials.php',
    'pages/material-details.php',
    'pages/visualizer.php',
    'pages/feedback.php',
    'pages/budget.php',
    'pages/contact.php',
    'pages/pricing.php',
    'pages/about.php',
    'pages/profile.php',
    'pages/gallery.php',
    'pages/wishlist.php',
];

const basePath = `d:\\College Project\\Interior Design Viewer`;

const replacements = [
    // General Structure
    { regex: /\bbg-white\b/g, replacement: 'glass-panel-dark' },
    { regex: /\bborder-structure\b/g, replacement: 'border-white/10' },
    { regex: /\btext-dark\b/g, replacement: 'text-white' },
    { regex: /\btext-muted\b/g, replacement: 'text-gray-400' },
    { regex: /\bbg-canvas\b/g, replacement: 'bg-white/5' },
    // Grays
    { regex: /\bbg-gray-50\b/g, replacement: 'bg-white/5' },
    { regex: /\bbg-gray-100\b/g, replacement: 'bg-white/10' },
    { regex: /\bhover:bg-gray-50\b/g, replacement: 'hover:bg-white/10' },
    { regex: /\bhover:bg-gray-100\b/g, replacement: 'hover:bg-white/10' },
    { regex: /\bhover:text-dark\b/g, replacement: 'hover:text-white' },
    { regex: /\bborder-gray-200\b/g, replacement: 'border-white/10' },
    { regex: /\bborder-gray-100\b/g, replacement: 'border-white/5' },
    { regex: /\bplaceholder-gray-400\b/g, replacement: 'placeholder-gray-500' },
    // Interactive Elements (Gradients & Neons)
    { regex: /\bbg-primary\b/g, replacement: 'bg-gradient-primary border-none shadow-[var(--shadow-primary)]' },
    { regex: /\btext-secondary\b/g, replacement: 'text-[#FF1493]' },
    { regex: /\bborder-secondary\b/g, replacement: 'border-[#FF1493]' },
    { regex: /\bhover:border-secondary\b/g, replacement: 'hover:border-[#FF1493]' },
    { regex: /\bfocus:border-secondary\b/g, replacement: 'focus:border-[#FF1493]' },
    { regex: /\bfocus:ring-secondary\b/g, replacement: 'focus:ring-[#FF1493]' },
    // Status Colors (Matching Dark Theme Aesthetics)
    { regex: /\bbg-red-50\b/g, replacement: 'bg-red-500/20' },
    { regex: /\bborder-red-200\b/g, replacement: 'border-red-500/30' },
    { regex: /\btext-red-600\b/g, replacement: 'text-red-400' },
    { regex: /\bhover:bg-red-50\b/g, replacement: 'hover:bg-red-500/20' },
    { regex: /\bbg-yellow-50\b/g, replacement: 'bg-yellow-500/20' },
    { regex: /\bborder-yellow-200\b/g, replacement: 'border-yellow-500/30' },
    { regex: /\btext-yellow-700\b/g, replacement: 'text-yellow-400' },
    { regex: /\bbg-green-50\b/g, replacement: 'bg-green-500/20' },
    { regex: /\bborder-green-200\b/g, replacement: 'border-green-500/30' },
    { regex: /\bborder-green-100\b/g, replacement: 'border-green-500/20' },
    { regex: /\btext-green-700\b/g, replacement: 'text-green-400' },
    { regex: /\btext-green-600\b/g, replacement: 'text-green-400' },
    { regex: /\bbg-orange-50\b/g, replacement: 'bg-orange-500/20' },
    { regex: /\bborder-orange-200\b/g, replacement: 'border-orange-500/30' },
    { regex: /\btext-orange-600\b/g, replacement: 'text-[#FF5E3A]' },
    { regex: /\btext-orange-500\b/g, replacement: 'text-[#FF5E3A]' },
    { regex: /\bbg-blue-50\b/g, replacement: 'bg-blue-500/20' },
    { regex: /\bborder-blue-200\b/g, replacement: 'border-blue-500/30' },
    { regex: /\btext-blue-600\b/g, replacement: 'text-gray-300' },
    { regex: /\bbg-purple-50\b/g, replacement: 'bg-purple-500/20' },
    { regex: /\bborder-purple-200\b/g, replacement: 'border-purple-500/30' }
];

let filesModifiedCount = 0;

for (const file of filesToProcess) {
    const filePath = path.join(basePath, file);
    
    if (fs.existsSync(filePath)) {
        let content = fs.readFileSync(filePath, 'utf8');
        let originalContent = content;
        
        for (const rule of replacements) {
            content = content.replace(rule.regex, rule.replacement);
        }
        
        if (content !== originalContent) {
            // Fix some over-replacements or specific logic
            // e.g. text-white in some places might be better as text-gradient
            // For headings: <h1 class="... text-white ..."> -> text-gradient
            content = content.replace(/<h([1-3])([^>]*)text-white([^>]*)>/g, '<h$1$2text-gradient mb-1$3>');
            
            fs.writeFileSync(filePath, content, 'utf8');
            console.log(`Updated theme for ${file}`);
            filesModifiedCount++;
        } else {
            console.log(`No changes made to ${file}`);
        }
    } else {
        console.log(`File not found: ${file}`);
    }
}

console.log(`\nSuccessfully updated ${filesModifiedCount} files to Metaverse Dark Theme.`);
