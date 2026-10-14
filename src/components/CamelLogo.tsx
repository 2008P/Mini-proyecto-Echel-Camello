import React from 'react';
import camelImg from '../assets/images/echele_camello_logo_1792000496435.jpg';

interface CamelLogoProps {
  size?: 'sm' | 'md' | 'lg' | 'xl';
  className?: string;
  showBadge?: boolean;
}

export const CamelLogo: React.FC<CamelLogoProps> = ({
  size = 'md',
  className = '',
  showBadge = true,
}) => {
  const sizeMap = {
    sm: 'w-8 h-8',
    md: 'w-10 h-10',
    lg: 'w-12 h-12',
    xl: 'w-16 h-16',
  };

  const imgSizeMap = {
    sm: 'w-7 h-7',
    md: 'w-9 h-9',
    lg: 'w-11 h-11',
    xl: 'w-14 h-14',
  };

  if (!showBadge) {
    return (
      <div className={`relative flex items-center justify-center shrink-0 ${sizeMap[size]} ${className}`}>
        <img
          src={camelImg}
          alt="Camello Logo"
          className="w-full h-full object-contain filter drop-shadow-sm"
        />
      </div>
    );
  }

  return (
    <div
      className={`relative rounded-2xl bg-gradient-to-br from-[#fff4f0] via-[#ffdbd0] to-[#f5b8a5] p-1 border border-[#ffdbd0] shadow-sm flex items-center justify-center shrink-0 group-hover:scale-105 transition-all overflow-hidden ${sizeMap[size]} ${className}`}
    >
      <img
        src={camelImg}
        alt="Échele Camello Logo"
        className={`${imgSizeMap[size]} object-contain mix-blend-multiply`}
      />
    </div>
  );
};

export const CamelIconSVG: React.FC<{ className?: string }> = ({ className = 'w-6 h-6' }) => (
  <svg
    viewBox="0 0 100 100"
    fill="currentColor"
    xmlns="http://www.w3.org/2000/svg"
    className={className}
    aria-hidden="true"
  >
    {/* Stylized Modern Camel Silhouette */}
    <path
      d="M20 33C20 29.5 22.5 27 26 27C27.5 27 31 28 32.5 29.5C34 26 36 24.5 39 25C40 25.2 41 26 41 27.5C41 29 40 30 38.5 30.5C37 31 36.5 32 36.5 34C36.5 36 38 41 39 44C42 43 45 40 48 37C51 34 54 32 58 32C64 32 68 36 71 42C74 38 78 36 82 38C86 40 88 44 87 49C86 53 84 57 85 62C85.5 65 87 67 86 70C85 73 82 72 81 68C80 63 78 60 76 60C74 60 73 63 72 68L70 82C69.5 84 68 85 66 85C64 85 63 83.5 63.5 81L66 65C66 63 65 62 63 62C61 62 60 63 60 66L58 81C57.5 83 56 84 54 84C52 84 51 82.5 51.5 80L54 63C54 60 52 58 50 58C47 58 44 61 43 65L41 81C40.5 83 39 84 37 84C35 84 34 82.5 34.5 80L38 56C38 53 36 49 34 46C32 43 31 39 31 36C30 37 28 38 26 38C23 38 20 35.5 20 33Z"
    />
  </svg>
);
