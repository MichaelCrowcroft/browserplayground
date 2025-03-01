import { clsx, type ClassValue } from 'clsx';
import { twMerge } from 'tailwind-merge';
import {formatDistance, parseISO} from 'date-fns';

const relativeDate = (date) => formatDistance(parseISO(date), new Date());

export function cn(...inputs: ClassValue[]) {
    return twMerge(clsx(inputs));
}

export {
    relativeDate,
};