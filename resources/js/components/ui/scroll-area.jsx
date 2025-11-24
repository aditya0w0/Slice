import * as React from "react";

const ScrollArea = React.forwardRef(({ className, children, style, ...props }, ref) => {
    return (
        <div
            ref={ref}
            className={className}
            style={{ overflowY: "auto", ...style }}
            {...props}
        >
            {children}
        </div>
    );
});
ScrollArea.displayName = "ScrollArea";

export { ScrollArea };
