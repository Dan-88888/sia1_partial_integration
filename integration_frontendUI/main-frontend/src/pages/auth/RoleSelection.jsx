import { useState } from "react";
import { useNavigate } from "react-router-dom";
import { HiEye, HiEyeOff, HiArrowLeft } from "react-icons/hi";
import logo from "../../assets/logo.png";

const roles = [
  { label: "Administrator", portalPath: "/admin-portal" },
  { label: "Instructor", portalPath: "/instructor-portal" },
  { label: "Student", portalPath: "/student-portal" },
];

function ForgotPasswordModal({ onClose }) {
  const [email, setEmail] = useState("");
  const [code, setCode] = useState("");
  const [newPassword, setNewPassword] = useState("");
  const [retypePassword, setRetypePassword] = useState("");

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center px-4">
      {/* Backdrop */}
      <div className="absolute inset-0 bg-black/60" onClick={onClose} />

      {/* Modal */}
      <div className="relative w-full max-w-sm rounded-2xl overflow-hidden shadow-2xl">
        {/* Purple header */}
        <div className="bg-[#7c3aed] px-6 py-4 flex items-center justify-between">
          <h2 className="text-white font-bold text-base">Forgot Password</h2>
          <button
            onClick={onClose}
            className="text-white/70 hover:text-white transition-colors text-lg leading-none"
          >
            ⛶
          </button>
        </div>

        {/* Body */}
        <div className="bg-white px-8 py-6 flex flex-col gap-4">
          {/* Email + Send Code */}
          <div className="flex items-end gap-3">
            <div className="flex-1 border-b border-[#7c3aed] pb-1">
              <input
                type="email"
                placeholder="Email"
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                className="w-full text-sm text-gray-700 placeholder-gray-400 outline-none bg-transparent"
              />
            </div>
            <button
              type="button"
              className="px-4 py-1.5 rounded-full text-white text-sm font-semibold"
              style={{ background: "linear-gradient(135deg, #38bdf8, #6366f1)" }}
            >
              Send Code
            </button>
          </div>

          {/* Input Code */}
          <div className="flex items-center gap-4">
            <label className="text-sm text-gray-500 w-28 text-right shrink-0">Input Code</label>
            <div className="flex-1 border-b border-[#7c3aed] pb-1">
              <input
                type="text"
                value={code}
                onChange={(e) => setCode(e.target.value)}
                className="w-full text-sm text-gray-700 outline-none bg-transparent"
              />
            </div>
          </div>

          {/* New Password */}
          <div className="flex items-center gap-4">
            <label className="text-sm text-gray-500 w-28 text-right shrink-0">New password</label>
            <div className="flex-1 border-b border-gray-400 pb-1">
              <input
                type="password"
                value={newPassword}
                onChange={(e) => setNewPassword(e.target.value)}
                className="w-full text-sm text-gray-700 outline-none bg-transparent"
              />
            </div>
          </div>

          {/* Retype Password */}
          <div className="flex items-center gap-4">
            <label className="text-sm text-gray-500 w-28 text-right shrink-0">Retype password</label>
            <div className="flex-1 border-b border-[#7c3aed] pb-1">
              <input
                type="password"
                value={retypePassword}
                onChange={(e) => setRetypePassword(e.target.value)}
                className="w-full text-sm text-gray-700 outline-none bg-transparent"
              />
            </div>
          </div>

          {/* OK / Cancel */}
          <div className="flex gap-3 mt-2">
            <button
              type="button"
              className="flex-1 py-2.5 rounded-full text-white font-semibold text-sm bg-[#22c55e] hover:bg-[#16a34a] transition-colors"
            >
              OK
            </button>
            <button
              type="button"
              onClick={onClose}
              className="flex-1 py-2.5 rounded-full text-white font-semibold text-sm bg-[#f97316] hover:bg-[#ea580c] transition-colors"
            >
              Cancel
            </button>
          </div>
        </div>
      </div>
    </div>
  );
}

export default function RoleSelection() {
  const navigate = useNavigate();
  const [selectedRole, setSelectedRole] = useState(null);
  const [userId, setUserId] = useState("");
  const [password, setPassword] = useState("");
  const [showUserId, setShowUserId] = useState(false);
  const [showPassword, setShowPassword] = useState(false);
  const [showForgot, setShowForgot] = useState(false);

  const handleLogin = (e) => {
    e.preventDefault();
  };

  return (
    <div className="relative h-screen w-screen overflow-hidden">
      {/* Fullscreen Video */}
      <video
        autoPlay
        loop
        muted
        playsInline
        controlsList="nodownload"
        onContextMenu={(e) => e.preventDefault()}
        style={{ pointerEvents: "none" }}
        className="absolute inset-0 w-full h-full object-cover"
      >
        <source src="/videos/parsu-sunrise720.mp4" type="video/mp4" />
      </video>

      {/* Dark gradient overlay */}
      <div className="absolute inset-0 bg-gradient-to-br from-black/70 via-black/50 to-black/40" />

      {/* Centered Card */}
      <div className="relative z-10 flex h-full items-center justify-center px-4">
        <div className="w-full max-w-sm px-10 py-10 flex flex-col items-center">

          {/* Logo */}
          <img
            src={logo}
            alt="Partido State University Seal"
            className="w-32 h-32 object-contain mb-5"
            style={{ filter: "drop-shadow(0 4px 16px rgba(0,0,0,0.8))" }}
          />

          {/* University Name */}
          <h1
            className="text-2xl font-bold text-white text-center leading-snug tracking-tight"
            style={{ textShadow: "0 2px 12px rgba(0,0,0,0.9), 0 1px 4px rgba(0,0,0,0.8)" }}
          >
            Partido State University
          </h1>
          <p
            className="text-sm text-white/80 mt-1 mb-10 text-center font-medium"
            style={{ textShadow: "0 1px 6px rgba(0,0,0,0.9)" }}
          >
            Goa, Camarines Sur
          </p>

          {!selectedRole ? (
            /* — Role Selection — */
            <div className="w-full flex flex-col gap-3">
              {roles.map((role) => (
                <button
                  key={role.label}
                  onClick={() => role.portalPath ? navigate(role.portalPath) : setSelectedRole(role)}
                  className="w-full py-4 rounded-xl font-semibold text-base tracking-wide transition-all duration-200 active:scale-[0.97]"
                  style={{
                    background: "rgba(255,255,255,0.15)",
                    border: "1px solid rgba(255,255,255,0.25)",
                    color: "white",
                  }}
                  onMouseEnter={(e) => {
                    e.currentTarget.style.background = "rgba(255,255,255,0.25)";
                    e.currentTarget.style.borderColor = "rgba(255,255,255,0.45)";
                  }}
                  onMouseLeave={(e) => {
                    e.currentTarget.style.background = "rgba(255,255,255,0.15)";
                    e.currentTarget.style.borderColor = "rgba(255,255,255,0.25)";
                  }}
                >
                  {role.label}
                </button>
              ))}

              <button
                type="button"
                onClick={() => navigate("/admission-form")}
                className="w-full py-3.5 rounded-xl font-semibold text-sm tracking-wide mt-1 transition-all duration-200 active:scale-[0.97]"
                style={{
                  background: "rgba(99,102,241,0.75)",
                  border: "1px solid rgba(129,132,255,0.5)",
                  color: "white",
                  boxShadow: "0 4px 20px rgba(99,102,241,0.35)",
                }}
                onMouseEnter={(e) => { e.currentTarget.style.background = "rgba(99,102,241,0.9)"; }}
                onMouseLeave={(e) => { e.currentTarget.style.background = "rgba(99,102,241,0.75)"; }}
              >
                Apply for Admission
              </button>
            </div>
          ) : (
            /* — Login Form — */
            <form onSubmit={handleLogin} className="w-full flex flex-col gap-4">
              {/* Role badge */}
              <div className="w-full py-3.5 rounded-xl bg-[#0d1b3e] text-white font-bold text-sm text-center tracking-wide shadow-md">
                {selectedRole.label}
              </div>

              {/* User ID */}
              <div className="flex flex-col gap-1">
                <div
                  className="flex items-center border-b pb-1"
                  style={{ borderColor: "rgba(255,255,255,0.6)" }}
                >
                  <input
                    type={showUserId ? "text" : "password"}
                    placeholder="User ID"
                    value={userId}
                    onChange={(e) => setUserId(e.target.value)}
                    className="flex-1 bg-transparent text-white text-sm outline-none font-medium"
                    style={{
                      textShadow: "0 1px 6px rgba(0,0,0,0.8)",
                      caretColor: "white",
                    }}
                  />
                  <button
                    type="button"
                    onClick={() => setShowUserId(!showUserId)}
                    className="transition-colors ml-2"
                    style={{ color: "rgba(255,255,255,0.75)", filter: "drop-shadow(0 1px 3px rgba(0,0,0,0.7))" }}
                  >
                    {showUserId ? <HiEye className="w-5 h-5" /> : <HiEyeOff className="w-5 h-5" />}
                  </button>
                </div>
              </div>

              {/* Password */}
              <div className="flex flex-col gap-1">
                <div
                  className="flex items-center border-b pb-1"
                  style={{ borderColor: "rgba(255,255,255,0.6)" }}
                >
                  <input
                    type={showPassword ? "text" : "password"}
                    placeholder="Password"
                    value={password}
                    onChange={(e) => setPassword(e.target.value)}
                    className="flex-1 bg-transparent text-white text-sm outline-none font-medium"
                    style={{
                      textShadow: "0 1px 6px rgba(0,0,0,0.8)",
                      caretColor: "white",
                    }}
                  />
                  <button
                    type="button"
                    onClick={() => setShowPassword(!showPassword)}
                    className="transition-colors ml-2"
                    style={{ color: "rgba(255,255,255,0.75)", filter: "drop-shadow(0 1px 3px rgba(0,0,0,0.7))" }}
                  >
                    {showPassword ? <HiEye className="w-5 h-5" /> : <HiEyeOff className="w-5 h-5" />}
                  </button>
                </div>
              </div>

              {/* Login */}
              <button
                type="submit"
                className="w-full py-3.5 rounded-xl font-bold text-sm text-white mt-1 transition-all duration-200 active:scale-[0.97]"
                style={{
                  background: "linear-gradient(135deg, #38bdf8, #6366f1)",
                  boxShadow: "0 4px 20px rgba(99,102,241,0.4)",
                }}
              >
                Login
              </button>

              {/* Forgot Password */}
              <button
                type="button"
                onClick={() => userId.trim() && setShowForgot(true)}
                disabled={!userId.trim()}
                className="w-full py-3.5 rounded-xl font-semibold text-sm transition-all duration-200 active:scale-[0.97]"
                style={{
                  background: "rgba(255,255,255,0.12)",
                  border: "1px solid rgba(255,255,255,0.3)",
                  color: userId.trim() ? "rgba(255,255,255,0.9)" : "rgba(255,255,255,0.3)",
                  cursor: userId.trim() ? "pointer" : "not-allowed",
                  textShadow: userId.trim() ? "0 1px 6px rgba(0,0,0,0.8)" : "none",
                }}
              >
                Forgot Password
              </button>

              {/* Back */}
              <button
                type="button"
                onClick={() => { setSelectedRole(null); setUserId(""); setPassword(""); }}
                className="flex items-center justify-center gap-1 text-xs mt-1 transition-colors font-medium"
                style={{
                  color: "rgba(255,255,255,0.65)",
                  textShadow: "0 1px 6px rgba(0,0,0,0.9)",
                }}
                onMouseEnter={(e) => { e.currentTarget.style.color = "rgba(255,255,255,0.95)"; }}
                onMouseLeave={(e) => { e.currentTarget.style.color = "rgba(255,255,255,0.65)"; }}
              >
                <HiArrowLeft className="w-3.5 h-3.5" /> Back to roles
              </button>
            </form>
          )}
        </div>
      </div>

      {/* Bottom tagline */}
      <div className="absolute bottom-6 left-0 right-0 text-center z-10">
        <p
          className="text-white/70 text-xs tracking-widest uppercase font-medium"
          style={{ textShadow: "0 1px 6px rgba(0,0,0,0.9)" }}
        >
          Mus Nak'ta Mga Partidoanon
        </p>
      </div>

      {/* Forgot Password Modal */}
      {showForgot && <ForgotPasswordModal onClose={() => setShowForgot(false)} />}
    </div>
  );
}
