import React from 'react'
import styles from './Button.module.css'

type ButtonProps = {
  isPrimary?: boolean
  children: React.ReactNode
}

export function Button({ isPrimary = true, children }: ButtonProps) {
  return (
    <button className={isPrimary ? styles['btn-primary'] : styles.btn}>
      {children}
    </button>
  )
}
