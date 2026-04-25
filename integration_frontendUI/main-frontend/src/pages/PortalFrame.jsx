import { useNavigate } from "react-router-dom";
import { HiArrowLeft } from "react-icons/hi";

export default function PortalFrame({ src, title }) {
  const navigate = useNavigate();

  return (
    <div style={{ position: "fixed", inset: 0, zIndex: 9999, background: "#000" }}>
      <button
        onClick={() => navigate("/")}
        style={{
          position: "absolute",
          top: 12,
          left: 12,
          zIndex: 10000,
          display: "flex",
          alignItems: "center",
          gap: 6,
          padding: "6px 14px",
          borderRadius: 8,
          background: "rgba(0,0,0,0.55)",
          color: "#fff",
          border: "1px solid rgba(255,255,255,0.25)",
          cursor: "pointer",
          fontSize: 13,
          fontWeight: 600,
          backdropFilter: "blur(6px)",
        }}
      >
        <HiArrowLeft /> Back to Main
      </button>
      <iframe
        src={src}
        title={title}
        style={{ width: "100%", height: "100%", border: "none" }}
        allow="same-origin"
      />
    </div>
  );
}
