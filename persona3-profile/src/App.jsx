import { useState, useEffect, useRef } from "react";

const moonPhases = ["🌑","🌒","🌓","🌔","🌕","🌖","🌗","🌘"];

const skills = [
  { name: "React / Next.js", level: 90, arcana: "The Magician" },
  { name: "TypeScript", level: 82, arcana: "The Hermit" },
  { name: "Node.js", level: 75, arcana: "The Emperor" },
  { name: "UI/UX Design", level: 85, arcana: "The Star" },
  { name: "Python", level: 70, arcana: "The Moon" },
  { name: "PostgreSQL", level: 68, arcana: "The Hierophant" },
];

const projects = [
  {
    title: "Tartarus Navigator",
    desc: "A full-stack dungeon management app built with Next.js and Prisma. Features real-time floor tracking and SEES squad assignment.",
    tags: ["Next.js", "Prisma", "TypeScript"],
    arcana: "Tower", color: "#3b82f6",
  },
  {
    title: "Shadow Analyzer",
    desc: "Machine learning model that classifies enemy types using TensorFlow.js. Trained on 10k+ shadow behavior patterns.",
    tags: ["Python", "TensorFlow", "FastAPI"],
    arcana: "Justice", color: "#6366f1",
  },
  {
    title: "Moonrise UI Kit",
    desc: "Open-source component library inspired by Dark Hour aesthetics. 40+ accessible components with dark-mode-first design.",
    tags: ["React", "Storybook", "CSS"],
    arcana: "Moon", color: "#8b5cf6",
  },
  {
    title: "Evoker Dashboard",
    desc: "Real-time analytics dashboard for tracking daily social links and stat progression. Syncs with calendar APIs.",
    tags: ["Vue.js", "D3.js", "Firebase"],
    arcana: "Death", color: "#06b6d4",
  },
];

const socials = [
  { label: "GitHub",   icon: "GH", href: "https://github.com/AElius25", desc: "AElius25" },
  { label: "LinkedIn", icon: "in", href: "#", desc: "Didik Weka Pratama" },
  { label: "Instagram",  icon: "IG",  href: "https://www.instagram.com/capt_lius/", desc: "@capt. lius" },
  { label: "Email",    icon: "@",  href: "#", desc: "didik.a7000@gmail.com" },
];

// ============================================================
//  VIDEO BACKGROUND
//  1. Taruh file video .mp4 kamu di folder  public/
//  2. Ganti nilai VIDEO_SRC di bawah sesuai nama file-mu
//     Contoh: file bernama "p3opening.mp4"  →  "/p3opening.mp4"
// ============================================================
const VIDEO_SRC = "/persona3-bg.mp4";

function VideoBackground() {
  return (
    <div style={{ position:"fixed", inset:0, zIndex:0, overflow:"hidden", pointerEvents:"none" }}>
      <video
        autoPlay muted loop playsInline
        style={{ position:"absolute", inset:0, width:"100%", height:"100%", objectFit:"cover", opacity:0.35 }}
      >
        <source src={VIDEO_SRC} type="video/mp4" />
      </video>
      {/* gradient overlay atas-bawah */}
      <div style={{ position:"absolute", inset:0, background:"linear-gradient(180deg,rgba(2,6,23,0.8) 0%,rgba(2,6,23,0.5) 50%,rgba(2,6,23,0.9) 100%)" }} />
      {/* blue tint — suasana Dark Hour */}
      <div style={{ position:"absolute", inset:0, background:"rgba(10,20,80,0.28)" }} />
      {/* scanline subtle */}
      <div style={{ position:"absolute", inset:0, backgroundImage:"repeating-linear-gradient(0deg,transparent,transparent 2px,rgba(0,0,0,0.03) 2px,rgba(0,0,0,0.03) 4px)" }} />
    </div>
  );
}

function MoonClock() {
  const [time, setTime]   = useState(new Date());
  const [phase, setPhase] = useState(4);
  useEffect(() => {
    const t = setInterval(() => { setTime(new Date()); setPhase(p => (p+1)%8); }, 3000);
    return () => clearInterval(t);
  }, []);
  return (
    <div style={{ display:"flex", alignItems:"center", gap:"8px", fontSize:"13px", color:"#94a3b8", fontFamily:"monospace" }}>
      <span style={{ fontSize:"18px" }}>{moonPhases[phase]}</span>
      <span>{time.toLocaleTimeString("id-ID",{ hour:"2-digit", minute:"2-digit" })}</span>
      <span style={{ color:"#3b82f6", marginLeft:"4px" }}>DARK HOUR</span>
    </div>
  );
}

function NavBar({ active, setActive }) {
  const items = ["Profile","Skills","Projects","Contact"];
  return (
    <nav style={{ position:"fixed", top:0, left:0, right:0, zIndex:100,
      background:"rgba(2,6,23,0.75)", backdropFilter:"blur(24px)",
      borderBottom:"1px solid rgba(59,130,246,0.2)",
      display:"flex", alignItems:"center", justifyContent:"space-between",
      padding:"0 2rem", height:"60px" }}>
      <div style={{ display:"flex", alignItems:"center", gap:"10px" }}>
        <div style={{ width:"32px", height:"32px", borderRadius:"50%",
          background:"linear-gradient(135deg,#1e40af,#3b82f6)",
          display:"flex", alignItems:"center", justifyContent:"center",
          fontSize:"13px", fontWeight:"700", color:"white",
          border:"2px solid rgba(99,102,241,0.5)" }}>P3</div>
        <span style={{ color:"#e2e8f0", fontWeight:"600", fontSize:"15px", letterSpacing:"0.05em" }}>SEES</span>
        <span style={{ color:"#475569", fontSize:"12px" }}>— PROFILE</span>
      </div>
      <MoonClock />
      <div style={{ display:"flex", gap:"4px" }}>
        {items.map(item => (
          <button key={item} onClick={() => setActive(item)} style={{
            background: active===item ? "rgba(59,130,246,0.2)" : "transparent",
            border: active===item ? "1px solid rgba(59,130,246,0.4)" : "1px solid transparent",
            color: active===item ? "#93c5fd" : "#64748b",
            padding:"6px 14px", borderRadius:"6px", cursor:"pointer",
            fontSize:"13px", fontWeight: active===item ? "600" : "400", transition:"all 0.2s",
          }}>{item}</button>
        ))}
      </div>
    </nav>
  );
}

function HeroSection() {
  const [blink, setBlink] = useState(true);
  useEffect(() => { const t = setInterval(() => setBlink(b=>!b), 500); return () => clearInterval(t); }, []);
  return (
    <section style={{ minHeight:"100vh", display:"flex", alignItems:"center", justifyContent:"center", padding:"80px 2rem 0" }}>
      <div style={{ maxWidth:"900px", width:"100%", display:"grid", gridTemplateColumns:"1fr 1fr", gap:"4rem", alignItems:"center" }}>
        <div>
          <div style={{ fontSize:"11px", letterSpacing:"0.3em", color:"#3b82f6", marginBottom:"16px", fontFamily:"monospace" }}>
            GEKKOUKAN HIGH SCHOOL // SEES MEMBER
          </div>
          <h1 style={{ fontSize:"clamp(2.5rem,5vw,4rem)", fontWeight:"800", color:"#f1f5f9", lineHeight:1.1, marginBottom:"8px", fontFamily:"Georgia,serif" }}>
            Didik<br /><span style={{ color:"#3b82f6" }}>Pratama</span>
          </h1>
          <div style={{ fontSize:"14px", color:"#64748b", marginBottom:"24px", fontFamily:"monospace" }}>
            {">"} Full-Stack Developer{blink ? "_" : " "}
          </div>
          <p style={{ color:"#94a3b8", lineHeight:1.8, fontSize:"15px", marginBottom:"32px" }}>
            The Wild Card. I wield multiple Persona — from React to Python,
            from databases to design. Every project is a new floor of Tartarus.
            Every bug is a Shadow to defeat.
          </p>
          <div style={{ display:"flex", gap:"12px" }}>
            <button style={{ background:"linear-gradient(135deg,#1e40af,#3b82f6)", border:"none", color:"white",
              padding:"12px 28px", borderRadius:"8px", cursor:"pointer", fontSize:"14px", fontWeight:"600", letterSpacing:"0.05em" }}>
              VIEW PROJECTS
            </button>
            <button style={{ background:"rgba(2,6,23,0.5)", backdropFilter:"blur(8px)",
              border:"1px solid rgba(59,130,246,0.4)", color:"#93c5fd",
              padding:"12px 28px", borderRadius:"8px", cursor:"pointer", fontSize:"14px" }}>
              DOWNLOAD CV
            </button>
          </div>
        </div>

        <div style={{ display:"flex", flexDirection:"column", alignItems:"center", gap:"24px" }}>
          <div style={{ position:"relative" }}>
            <div style={{ width:"220px", height:"220px", borderRadius:"50%",
              background:"linear-gradient(135deg,rgba(15,23,42,0.8),rgba(30,58,95,0.9))",
              border:"3px solid rgba(59,130,246,0.5)",
              display:"flex", alignItems:"center", justifyContent:"center",
              fontSize:"90px", backdropFilter:"blur(10px)",
              boxShadow:"0 0 60px rgba(59,130,246,0.25),inset 0 0 40px rgba(0,0,50,0.5)" }}>
              🌑
            </div>
            <div style={{ position:"absolute", top:"-8px", right:"-8px",
              width:"48px", height:"48px", borderRadius:"50%",
              background:"linear-gradient(135deg,#1d4ed8,#6366f1)",
              display:"flex", alignItems:"center", justifyContent:"center",
              fontSize:"20px", border:"2px solid #020617" }}>☽</div>
          </div>
          <div style={{ display:"grid", gridTemplateColumns:"1fr 1fr", gap:"12px", width:"100%" }}>
            {[{label:"Projects",value:"24"},{label:"Arcana",value:"XII"},{label:"Level",value:"77"},{label:"Rank",value:"MAX"}].map(s => (
              <div key={s.label} style={{ background:"rgba(15,23,42,0.6)", backdropFilter:"blur(12px)",
                border:"1px solid rgba(59,130,246,0.2)", borderRadius:"8px", padding:"16px", textAlign:"center" }}>
                <div style={{ fontSize:"22px", fontWeight:"700", color:"#93c5fd", fontFamily:"monospace" }}>{s.value}</div>
                <div style={{ fontSize:"11px", color:"#475569", letterSpacing:"0.1em", marginTop:"4px" }}>{s.label.toUpperCase()}</div>
              </div>
            ))}
          </div>
        </div>
      </div>
    </section>
  );
}

function SkillBar({ name, level, arcana, delay }) {
  const [animated, setAnimated] = useState(false);
  const ref = useRef();
  useEffect(() => {
    const obs = new IntersectionObserver(([e]) => { if (e.isIntersecting) setAnimated(true); });
    if (ref.current) obs.observe(ref.current);
    return () => obs.disconnect();
  }, []);
  return (
    <div ref={ref} style={{ marginBottom:"20px" }}>
      <div style={{ display:"flex", justifyContent:"space-between", marginBottom:"8px" }}>
        <div>
          <span style={{ color:"#e2e8f0", fontSize:"14px", fontWeight:"500" }}>{name}</span>
          <span style={{ color:"#3b82f6", fontSize:"11px", marginLeft:"10px", fontFamily:"monospace" }}>// {arcana}</span>
        </div>
        <span style={{ color:"#93c5fd", fontSize:"13px", fontFamily:"monospace" }}>{level}%</span>
      </div>
      <div style={{ height:"6px", background:"rgba(30,58,138,0.3)", borderRadius:"3px", overflow:"hidden" }}>
        <div style={{ height:"100%", width: animated ? `${level}%` : "0%",
          background:"linear-gradient(90deg,#1e40af,#6366f1,#93c5fd)", borderRadius:"3px",
          transition:`width 1.2s ease ${delay}s`, boxShadow:"0 0 8px rgba(99,102,241,0.5)" }} />
      </div>
    </div>
  );
}

function SkillsSection() {
  return (
    <section style={{ padding:"6rem 2rem", maxWidth:"900px", margin:"0 auto" }}>
      <div style={{ background:"rgba(2,6,23,0.5)", backdropFilter:"blur(16px)",
        border:"1px solid rgba(59,130,246,0.1)", borderRadius:"16px", padding:"40px", marginBottom:"28px" }}>
        <div style={{ fontSize:"11px", letterSpacing:"0.3em", color:"#3b82f6", marginBottom:"8px", fontFamily:"monospace" }}>PERSONA ABILITIES</div>
        <h2 style={{ fontSize:"2.5rem", fontWeight:"700", color:"#f1f5f9", fontFamily:"Georgia,serif", marginBottom:"8px" }}>
          Skills & <span style={{ color:"#6366f1" }}>Arcana</span>
        </h2>
        <p style={{ color:"#64748b", fontSize:"15px", marginBottom:"32px" }}>Each skill is bound to an Arcana — mastered through Social Links.</p>
        <div style={{ display:"grid", gridTemplateColumns:"1fr 1fr", gap:"0 3rem" }}>
          {skills.map((s,i) => <SkillBar key={s.name} {...s} delay={i*0.15} />)}
        </div>
      </div>
      <div>
        {["Git","Docker","Figma","Tailwind","GraphQL","Redis","AWS","Jest"].map(tag => (
          <span key={tag} style={{ display:"inline-block", margin:"4px",
            background:"rgba(30,58,138,0.4)", backdropFilter:"blur(8px)",
            border:"1px solid rgba(59,130,246,0.25)", color:"#93c5fd",
            padding:"6px 14px", borderRadius:"20px", fontSize:"12px", fontFamily:"monospace" }}>{tag}</span>
        ))}
      </div>
    </section>
  );
}

function ProjectCard({ project }) {
  const [hovered, setHovered] = useState(false);
  return (
    <div onMouseEnter={() => setHovered(true)} onMouseLeave={() => setHovered(false)}
      style={{ background: hovered ? "rgba(30,58,138,0.3)" : "rgba(15,23,42,0.55)",
        backdropFilter:"blur(16px)",
        border:`1px solid ${hovered ? "rgba(99,102,241,0.5)" : "rgba(59,130,246,0.15)"}`,
        borderRadius:"12px", padding:"28px", transition:"all 0.3s ease",
        transform: hovered ? "translateY(-4px)" : "none" }}>
      <div style={{ display:"flex", justifyContent:"space-between", alignItems:"flex-start", marginBottom:"12px" }}>
        <span style={{ fontSize:"10px", letterSpacing:"0.2em", fontFamily:"monospace",
          color:"#3b82f6", background:"rgba(30,58,138,0.4)", padding:"4px 10px", borderRadius:"4px" }}>
          THE {project.arcana.toUpperCase()}
        </span>
        <div style={{ width:"10px", height:"10px", borderRadius:"50%", background:project.color, opacity:0.8 }} />
      </div>
      <h3 style={{ color:"#e2e8f0", fontSize:"17px", fontWeight:"600", marginBottom:"10px" }}>{project.title}</h3>
      <p style={{ color:"#64748b", fontSize:"13px", lineHeight:1.7, marginBottom:"16px" }}>{project.desc}</p>
      <div style={{ display:"flex", gap:"8px", flexWrap:"wrap" }}>
        {project.tags.map(t => (
          <span key={t} style={{ background:"rgba(99,102,241,0.1)", color:"#818cf8",
            border:"1px solid rgba(99,102,241,0.2)", padding:"3px 10px", borderRadius:"4px",
            fontSize:"11px", fontFamily:"monospace" }}>{t}</span>
        ))}
      </div>
    </div>
  );
}

function ProjectsSection() {
  return (
    <section style={{ padding:"6rem 2rem", maxWidth:"960px", margin:"0 auto" }}>
      <div style={{ marginBottom:"40px" }}>
        <div style={{ fontSize:"11px", letterSpacing:"0.3em", color:"#3b82f6", marginBottom:"8px", fontFamily:"monospace" }}>TARTARUS FLOORS</div>
        <h2 style={{ fontSize:"2.5rem", fontWeight:"700", color:"#f1f5f9", fontFamily:"Georgia,serif" }}>
          Featured <span style={{ color:"#6366f1" }}>Projects</span>
        </h2>
        <p style={{ color:"#64748b", marginTop:"12px", fontSize:"15px" }}>Adventures conquered in the Dark Hour.</p>
      </div>
      <div style={{ display:"grid", gridTemplateColumns:"1fr 1fr", gap:"20px" }}>
        {projects.map(p => <ProjectCard key={p.title} project={p} />)}
      </div>
    </section>
  );
}

function ContactSection() {
  return (
    <section style={{ padding:"6rem 2rem", maxWidth:"900px", margin:"0 auto" }}>
      <div style={{ marginBottom:"40px" }}>
        <div style={{ fontSize:"11px", letterSpacing:"0.3em", color:"#3b82f6", marginBottom:"8px", fontFamily:"monospace" }}>SOCIAL LINKS</div>
        <h2 style={{ fontSize:"2.5rem", fontWeight:"700", color:"#f1f5f9", fontFamily:"Georgia,serif" }}>
          Get in <span style={{ color:"#6366f1" }}>Touch</span>
        </h2>
        <p style={{ color:"#64748b", marginTop:"12px", fontSize:"15px", maxWidth:"480px" }}>
          Max out the Social Links. Whether it's a collab, a job, or just a chat — available during and after the Dark Hour.
        </p>
      </div>
      <div style={{ display:"grid", gridTemplateColumns:"repeat(2,1fr)", gap:"16px", marginBottom:"40px" }}>
        {socials.map(s => (
          <a key={s.label} href={s.href} style={{ textDecoration:"none" }}>
            <div style={{ background:"rgba(15,23,42,0.55)", backdropFilter:"blur(16px)",
              border:"1px solid rgba(59,130,246,0.2)", borderRadius:"12px", padding:"20px 24px",
              display:"flex", alignItems:"center", gap:"16px", transition:"all 0.2s" }}>
              <div style={{ width:"44px", height:"44px", borderRadius:"10px",
                background:"linear-gradient(135deg,#1e3a5f,#1e40af)",
                display:"flex", alignItems:"center", justifyContent:"center",
                color:"#93c5fd", fontSize:"14px", fontWeight:"700", fontFamily:"monospace", flexShrink:0 }}>{s.icon}</div>
              <div>
                <div style={{ color:"#e2e8f0", fontSize:"14px", fontWeight:"500" }}>{s.label}</div>
                <div style={{ color:"#475569", fontSize:"12px", fontFamily:"monospace" }}>{s.desc}</div>
              </div>
            </div>
          </a>
        ))}
      </div>
      <div style={{ background:"rgba(15,23,42,0.55)", backdropFilter:"blur(20px)",
        border:"1px solid rgba(59,130,246,0.2)", borderRadius:"16px", padding:"40px", textAlign:"center" }}>
        <div style={{ fontSize:"32px", marginBottom:"14px" }}>☽</div>
        <h3 style={{ color:"#e2e8f0", fontSize:"20px", fontWeight:"600", marginBottom:"8px" }}>Open to Opportunities</h3>
        <p style={{ color:"#64748b", fontSize:"14px", lineHeight:1.7, marginBottom:"22px" }}>
          Currently seeking full-time roles and freelance projects.<br />
          Available during normal hours — and the Dark Hour.
        </p>
        <a href="mailto:minato@gekkoukan.jp" style={{ display:"inline-block",
          background:"linear-gradient(135deg,#1e40af,#6366f1)", color:"white",
          padding:"12px 32px", borderRadius:"8px", textDecoration:"none",
          fontSize:"14px", fontWeight:"600", letterSpacing:"0.05em" }}>SEND MESSAGE</a>
      </div>
      <div style={{ textAlign:"center", marginTop:"40px", paddingTop:"28px", borderTop:"1px solid rgba(59,130,246,0.1)" }}>
        <p style={{ color:"#334155", fontSize:"12px", fontFamily:"monospace" }}>
          Memento Mori — "Please take care of yourself." © {new Date().getFullYear()} SEES
        </p>
      </div>
    </section>
  );
}

export default function App() {
  const [active, setActive] = useState("Profile");
  const sectionMap = {
    Profile:  <HeroSection />,
    Skills:   <SkillsSection />,
    Projects: <ProjectsSection />,
    Contact:  <ContactSection />,
  };
  return (
    <div style={{ minHeight:"100vh", background:"#020617", position:"relative", fontFamily:"'Segoe UI',sans-serif" }}>
      <style>{`
        *{box-sizing:border-box;margin:0;padding:0;}
        ::-webkit-scrollbar{width:4px;}
        ::-webkit-scrollbar-track{background:#020617;}
        ::-webkit-scrollbar-thumb{background:rgba(59,130,246,0.3);border-radius:2px;}
        @keyframes fadeIn{from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:none}}
        .section-enter{animation:fadeIn 0.5s ease forwards;}
      `}</style>

      <VideoBackground />
      <NavBar active={active} setActive={setActive} />

      <main style={{ position:"relative", zIndex:1 }}>
        <div key={active} className="section-enter">
          {sectionMap[active]}
        </div>
      </main>
    </div>
  );
}