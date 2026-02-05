import { useState, useEffect } from 'react';
import { getStorageImage } from '../Utils/image';

export default function StorageImage({ path, name, className, alt, ...props }) {
    const [imgSrc, setImgSrc] = useState('');
    const [hasError, setHasError] = useState(false);

    useEffect(() => {
        // Reset state when path changes
        setHasError(false);
        setImgSrc(getStorageImage(path, name));
    }, [path, name]);

    const handleError = () => {
        if (!hasError) {
            setHasError(true);
            setImgSrc(getStorageImage(null, name));
        }
    };

    return (
        <img
            src={imgSrc}
            alt={alt || name}
            className={className}
            onError={handleError}
            {...props}
        />
    );
}
