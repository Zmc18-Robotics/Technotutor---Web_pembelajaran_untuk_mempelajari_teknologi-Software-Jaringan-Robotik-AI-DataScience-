<?php
$game=$_GET['name']??'';
$games=[
 'color'=>['title'=>'Cari Warna Berbeda','icon'=>'🎨'],
 'math'=>['title'=>'Hitung Kilat','icon'=>'➗'],
 'dots'=>['title'=>'Hitung Titik','icon'=>'🔵'],
];
if(!isset($games[$game]))$game='color';
$best=(int)($D['game_scores'][$me][$game]??0);
$savedScore=filter_var($_GET['score']??null,FILTER_VALIDATE_INT);
?>
<div class="wrap game-wrap">
<div class="course-actions"><a class="btn g" href="?p=games">← Semua game</a><a class="btn g" href="?">Menu utama</a></div>
<section class="card game-panel">
<div class="game-heading"><span class="ic"><?=$games[$game]['icon']?></span><div><h2><?=h($games[$game]['title'])?></h2><p class="muted">Skor terbaik Anda: <b><?=$best?></b></p></div></div>
<?php if($savedScore!==false):?><div class="ok2">Permainan selesai. Skor ronde terakhir: <?=(int)$savedScore?>. Skor terbaik disimpan untuk akun Anda.</div><?php endif;?>
<?php if($flash):?><div class="<?=h($flashType??'err')?>"><?=h($flash)?></div><?php endif;?>
<p id="game-status" class="game-status">Tekan mulai saat siap.</p>
<div id="game-stage" class="game-stage"></div>
<button id="game-start" class="btn" type="button">Mulai permainan</button>
<form id="game-score-form" method="post" hidden><input type="hidden" name="csrf" value="<?=h($csrfToken)?>"><input type="hidden" name="a" value="savegamescore"><input type="hidden" name="game" value="<?=h($game)?>"><input id="game-score-value" type="hidden" name="score" value="0"><button class="btn" type="submit">Simpan skor</button></form>
</section></div>
<script>
(() => {
    const game = <?=json_encode($game)?>;
    const stage = document.getElementById('game-stage');
    const status = document.getElementById('game-status');
    const start = document.getElementById('game-start');
    const scoreForm = document.getElementById('game-score-form');
    const scoreInput = document.getElementById('game-score-value');
    let score = 0;
    let timer = null;
    let hearts = 3;
    let ended = false;
    const button = (label, className = 'game-choice') => {
        const item = document.createElement('button');
        item.type = 'button';
        item.className = className;
        item.textContent = label;
        return item;
    };
    const finish = () => {
        if (ended) return;
        ended = true;
        window.clearInterval(timer);
        status.textContent = `Permainan selesai. Skor Anda: ${score}. Menyimpan skor terbaik…`;
        scoreInput.value = String(score);
        scoreForm.hidden = false;
        start.hidden = true;
        window.setTimeout(() => scoreForm.requestSubmit(), 500);
    };
    const addPoint = () => { score += 1; };

    const nextColorRound = () => {
        const round = score + 1;
        const count = Math.min(9 + Math.floor(score / 2) * 2, 49);
        const columns = Math.min(3 + Math.floor(score / 4), 7);
        const target = Math.floor(Math.random() * count);
        const hue = Math.floor(Math.random() * 360);
        const light = 48 + Math.floor(Math.random() * 12);
        const difference = Math.max(2, 24 - score * 0.65);
        stage.innerHTML = '';
        stage.className = 'game-stage color-board';
        stage.style.setProperty('--game-columns', columns);
        status.textContent = `Ronde ${round} · Skor ${score} · Cari kotak dengan warna berbeda`;
        for (let index = 0; index < count; index += 1) {
            const tile = button('');
            tile.classList.add('color-tile');
            tile.style.backgroundColor = `hsl(${hue} 65% ${light + (index === target ? difference : 0)}%)`;
            tile.addEventListener('click', () => {
                if (ended) return;
                if (index !== target) finish();
                else { addPoint(); nextColorRound(); }
            });
            stage.append(tile);
        }
    };

    const nextMathQuestion = () => {
        const a = Math.floor(Math.random() * (10 + score * 3)) + 1;
        const b = Math.floor(Math.random() * (10 + score * 2)) + 1;
        const subtract = Math.random() < 0.35;
        const answer = subtract ? a - b : a + b;
        status.textContent = `Waktu tersisa ${Math.ceil(remaining)} detik · Skor ${score}`;
        stage.innerHTML = '';
        stage.className = 'game-stage math-stage';
        const prompt = document.createElement('h3');
        prompt.textContent = `${a} ${subtract ? '−' : '+'} ${b} = ?`;
        const input = document.createElement('input');
        input.type = 'number';
        input.id = 'math-answer';
        input.setAttribute('aria-label', 'Jawaban hitungan');
        input.autocomplete = 'off';
        input.required = true;
        const submit = button('Jawab', 'btn');
        const check = (event) => {
            event.preventDefault();
            if (Number(input.value) === answer) { addPoint(); nextMathQuestion(); }
            else { input.value = ''; input.focus(); status.textContent = `Belum tepat · Waktu ${Math.ceil(remaining)} detik · Skor ${score}`; }
        };
        submit.addEventListener('click', check);
        input.addEventListener('keydown', (event) => { if (event.key === 'Enter') check(event); });
        stage.append(prompt, input, submit);
        input.focus();
    };
    let remaining = 60;

    const nextDotsRound = () => {
        const count = 3 + Math.floor(Math.random() * Math.min(10, 3 + score));
        status.textContent = `Skor ${score} · Nyawa ${'♥'.repeat(hearts)}${'♡'.repeat(3 - hearts)} · Hafalkan jumlah titik`;
        stage.innerHTML = '';
        stage.className = 'game-stage dot-stage';
        const dots = document.createElement('div');
        dots.className = 'dot-display';
        for (let index = 0; index < count; index += 1) {
            const dot = document.createElement('span');
            dot.className = 'memory-dot';
            dots.append(dot);
        }
        stage.append(dots);
        window.setTimeout(() => {
            if (ended) return;
            dots.remove();
            status.textContent = `Skor ${score} · Nyawa ${'♥'.repeat(hearts)}${'♡'.repeat(3 - hearts)} · Berapa jumlah titik tadi?`;
            const input = document.createElement('input');
            input.type = 'number';
            input.min = '0';
            input.setAttribute('aria-label', 'Jumlah titik');
            input.required = true;
            const submit = button('Periksa', 'btn');
            let answered = false;
            const check = (event) => {
                event.preventDefault();
                if (answered) return;
                answered = true;
                input.disabled = true;
                submit.disabled = true;
                if (Number(input.value) === count) { addPoint(); nextDotsRound(); }
                else {
                    hearts -= 1;
                    if (hearts <= 0) finish();
                    else { status.textContent = `Jumlahnya ${count}. Nyawa tersisa ${'♥'.repeat(hearts)}${'♡'.repeat(3 - hearts)}.`; window.setTimeout(nextDotsRound, 900); }
                }
            };
            submit.addEventListener('click', check);
            input.addEventListener('keydown', (event) => { if (event.key === 'Enter') check(event); });
            stage.append(input, submit);
            input.focus();
        }, 1100);
    };

    start.addEventListener('click', () => {
        score = 0;
        hearts = 3;
        ended = false;
        scoreForm.hidden = true;
        start.disabled = true;
        start.textContent = 'Permainan berjalan';
        if (game === 'color') nextColorRound();
        if (game === 'math') {
            remaining = 60;
            nextMathQuestion();
            timer = window.setInterval(() => {
                remaining -= 1;
                if (remaining <= 0) finish();
                else if (!ended) status.textContent = `Waktu tersisa ${remaining} detik · Skor ${score}`;
            }, 1000);
        }
        if (game === 'dots') nextDotsRound();
    });
})();
</script>
