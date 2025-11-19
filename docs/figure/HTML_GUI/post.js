// 最低限のUIロジック：投稿作成、いいね、コメント、localStorageによる簡易永続化

(function(){
  // Utilities
  const q = s => document.querySelector(s);
  const qa = s => Array.from(document.querySelectorAll(s));

  const feedEl = q('#feed');
  const template = q('#post-template');
  const storageKey = 'scenetrip_demo_posts_v1';

  // サンプルユーザー情報（変更可）
  const currentUser = {
    name: 'あなた',
    avatar: 'https://avatars.githubusercontent.com/u/583231?v=4'
  };

  // 初期化：既存投稿読み込み（localStorage）
  let posts = loadPosts();

  // DOM要素
  const composerText = q('#composer-text');
  const composerImage = q('#composer-image');
  const composerPreview = q('#composer-preview');
  const postBtn = q('#post-btn');

  // 画像プレビュー処理
  composerImage.addEventListener('change', (e) => {
    const file = e.target.files[0];
    if(!file) { composerPreview.innerHTML = ''; composerPreview.style.display = 'none'; return; }
    const reader = new FileReader();
    reader.onload = () => {
      composerPreview.style.display = 'block';
      composerPreview.innerHTML = `<img src="${reader.result}" alt="preview">`;
    };
    reader.readAsDataURL(file);
  });

  // 自動リサイズ
  composerText.addEventListener('input', (e) => {
    e.target.style.height = 'auto';
    e.target.style.height = (e.target.scrollHeight) + 'px';
  });

  // 投稿ボタン
  postBtn.addEventListener('click', async () => {
    const text = composerText.value.trim();
    const file = composerImage.files[0];
    let imgData = '';
    if(file){
      imgData = await readFileAsDataURL(file);
    }
    if(!text && !imgData) {
      alert('テキストか画像を追加してください。');
      return;
    }
    const post = {
      id: 'p_' + Date.now(),
      author: currentUser.name,
      avatar: currentUser.avatar,
      time: new Date().toISOString(),
      text,
      image: imgData,
      likes: 0,
      liked: false,
      comments: []
    };
    posts.unshift(post);
    savePosts();
    renderFeed();
    // リセット
    composerText.value = '';
    composerImage.value = '';
    composerPreview.innerHTML = '';
    composerPreview.style.display = 'none';
    composerText.style.height = 'auto';
  });

  // レンダリング
  function renderFeed(){
    feedEl.innerHTML = '';
    if(posts.length === 0){
      const empty = document.createElement('div');
      empty.className = 'empty';
      empty.textContent = 'まだ投稿がありません。さっそく投稿してみましょう！';
      feedEl.appendChild(empty);
      return;
    }
    posts.forEach(post => {
      const frag = template.content.cloneNode(true);
      const article = frag.querySelector('.post-card');
      frag.querySelector('.post-avatar').src = post.avatar;
      frag.querySelector('.post-author').textContent = post.author;
      frag.querySelector('.post-time').textContent = timeAgo(new Date(post.time));
      frag.querySelector('.post-body').textContent = post.text || '';
      const imgEl = frag.querySelector('.post-image');
      if(post.image){
        imgEl.src = post.image;
        imgEl.style.display = 'block';
      } else {
        imgEl.style.display = 'none';
      }
      const likeBtn = frag.querySelector('.btn-like');
      const likeCount = frag.querySelector('.like-count');
      likeCount.textContent = post.likes;
      if(post.liked) likeBtn.classList.add('active');

      const commentBtn = frag.querySelector('.btn-comment');
      const commentCount = frag.querySelector('.comment-count');
      commentCount.textContent = post.comments.length;

      // イベントバインド
      likeBtn.addEventListener('click', () => {
        post.liked = !post.liked;
        post.likes += post.liked ? 1 : -1;
        savePosts();
        renderFeed();
      });
      // コメント開閉
      const commentsSection = frag.querySelector('.comments');
      const commentsList = frag.querySelector('.comments-list');
      const commentInput = frag.querySelector('.comment-input');
      const commentSend = frag.querySelector('.comment-send');
      commentBtn.addEventListener('click', () => {
        const open = commentsSection.getAttribute('aria-hidden') === 'true';
        commentsSection.setAttribute('aria-hidden', !open);
        if(open) {
          renderComments();
        }
      });
      // コメント送信
      commentSend.addEventListener('click', () => {
        const txt = commentInput.value.trim();
        if(!txt) return;
        post.comments.push({
          id: 'c_' + Date.now(),
          author: currentUser.name,
          text: txt,
          time: new Date().toISOString()
        });
        commentInput.value = '';
        savePosts();
        renderFeed();
      });

      function renderComments(){
        commentsList.innerHTML = '';
        if(post.comments.length === 0) {
          commentsList.innerHTML = '<div class="comment-item" style="color:var(--muted)">コメントはまだありません。</div>';
          return;
        }
        post.comments.forEach(c => {
          const div = document.createElement('div');
          div.className = 'comment-item';
          div.innerHTML = `<strong>${escapeHtml(c.author)}</strong> <span style="color:var(--muted);font-size:12px">(${timeAgo(new Date(c.time))})</span><div>${escapeHtml(c.text)}</div>`;
          commentsList.appendChild(div);
        });
      }

      feedEl.appendChild(frag);
    });
  }

  // 保存・読み込み
  function savePosts(){
    try{
      localStorage.setItem(storageKey, JSON.stringify(posts));
    }catch(e){}
  }
  function loadPosts(){
    try{
      const raw = localStorage.getItem(storageKey);
      if(!raw) return samplePosts();
      return JSON.parse(raw);
    }catch(e){
      return samplePosts();
    }
  }

  // サンプル投稿（最初の表示用）
  function samplePosts(){
    return [
      {
        id: 'p_sample_1',
        author: 'S2666',
        avatar: 'https://avatars.githubusercontent.com/u/3369400?v=4',
        time: new Date(Date.now() - 1000 * 60 * 60).toISOString(),
        text: '先日行ったカフェが最高だった！窓際の席でゆっくりできます ☕️',
        image: '',
        likes: 3,
        liked: false,
        comments: [{ id: 'c1', author: 'alice', text: '写真見たい！', time: new Date(Date.now() - 1000 * 60 * 30).toISOString() }]
      },
      {
        id: 'p_sample_2',
        author: 'bob',
        avatar: 'https://avatars.githubusercontent.com/u/583231?v=4',
        time: new Date(Date.now() - 1000 * 60 * 60 * 24).toISOString(),
        text: 'この間読んだマンガが面白かった。おすすめです。',
        image: '',
        likes: 1,
        liked: false,
        comments: []
      }
    ];
  }

  // ヘルパー
  function readFileAsDataURL(file){
    return new Promise((res, rej) => {
      const r = new FileReader();
      r.onload = () => res(r.result);
      r.onerror = rej;
      r.readAsDataURL(file);
    });
  }
  function timeAgo(date){
    const s = Math.floor((Date.now() - date.getTime()) / 1000);
    if(s < 60) return `${s}秒前`;
    if(s < 3600) return `${Math.floor(s/60)}分前`;
    if(s < 86400) return `${Math.floor(s/3600)}時間前`;
    return `${Math.floor(s/86400)}日前`;
  }
  function escapeHtml(s){ return String(s).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c])); }

  // 初回レンダー
  renderFeed();

  // 画面ロード時の補助（フォーカスなど）
  window.addEventListener('load', () => {
    // composerに軽いヒント
  });

})();