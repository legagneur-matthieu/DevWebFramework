document.addEventListener('securitypolicyviolation', (e) => {
    const report = {
        'csp-report': {
            'document-uri': document.location.href,
            'referrer': document.referrer,
            'violated-directive': e.violatedDirective,
            'effective-directive': e.effectiveDirective,
            'original-policy': e.originalPolicy,
            'blocked-uri': e.blockedURI,
            'status-code': e.statusCode,
            'source-file': e.sourceFile,
            'line-number': e.lineNumber,
            'column-number': e.columnNumber,
            'sample': e.sample || '',
            'disposition': e.disposition,
            'user-agent': navigator.userAgent
        }
    };
    fetch('/commun/csp.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/csp-report; charset=utf-8'
        },
        body: JSON.stringify(report)
    }).catch(err => console.warn('Erreur envoi rapport CSP:', err));
});
document.addEventListener('DOMContentLoaded', () => {
    const scripts = document.querySelectorAll('script:not([nonce])');

    scripts.forEach(script => {
        const warning = document.createElement('p');
        warning.className = 'alert alert-warning';
        let tooltip = '';

        if (script.src) {
            tooltip = `SRC : ${script.src}`;
        } else {
            const content = script.textContent.trim();
            tooltip = content.length > 500
                    ? content.substring(0, 500) + '\n...'
                    : content;
        }

        warning.title = tooltip;
        warning.innerHTML = `
            <strong>Violation CSP : </strong>Script sans nonce !</p>
        `;

        script.parentNode.insertBefore(warning, script);
    });
});